<?php

namespace App\Services\Report\Strategies;

use App\Repositories\Report\UltrasoundReportRepository;
use App\Services\Report\BaseReportService;
use App\Services\Report\Contracts\ReportStrategy;
use Illuminate\Support\Collection;

class UltrasoundReportStrategy extends BaseReportService implements ReportStrategy
{
    public function __construct(
        private readonly UltrasoundReportRepository $repo
    ) {}

    public function name(): string
    {
        return 'Reporte de Ecografía (Inicio de Protocolo)';
    }

    public function validate(array $filters): ?array
    {
        // Nada extra por ahora (date validation ya la hace el Runner).
        return null;
    }

    public function fetch(array $filters)
    {
        return [
            'rows' => $this->repo->fetchRows($filters),
            'hato_objetivo' => $this->repo->countHatoObjetivo($filters),
        ];
    }

    public function build(array $filters, $fetched): array
    {
        /** @var Collection $rows */
        $rows = $fetched['rows'];
        $hatoObjetivo = $fetched['hato_objetivo'];

        $totalRecords = $rows->count();
        $evaluados = $rows->pluck('control_bovine_id')->unique()->count(); // Hato evaluado (animales con ecografía)
        // Nota: si por error registran 2 eco al mismo animal, aquí se cuenta 1.

        // Conteos por estado (según tu enum)
        $implanted = $rows->where('status', 'implanted')->pluck('control_bovine_id')->unique()->count();
        $pregnant  = $rows->where('status', 'pregnant')->pluck('control_bovine_id')->unique()->count();
        $discarded = $rows->where('status', 'discarded')->pluck('control_bovine_id')->unique()->count();

        // Refugos (texto no vacío)
        $refugos = $rows->filter(fn($r) => $this->notEmpty($r->regufo))
            ->pluck('control_bovine_id')->unique()->count();

        // Vitaminas/minerales
        $withVitamins = $rows->filter(fn($r) => (int)$r->vitamins_and_minerals === 1)
            ->pluck('control_bovine_id')->unique()->count();

        // Efectividad real (interpretación operativa típica):
        // % animales trabajados = implantados / evaluados
        // (porque “trabajado” en inicio de protocolo = entra a tratamiento)
        $efectividadRealPct = $this->pct($implanted, $evaluados);

        // Faltantes vs hato objetivo (solo si hay control_id)
        $faltantes = $faltantesPct = $coberturaPct = null;
        $faltantes = $faltantesPct = $coberturaPct = 0;
        if (!is_null($hatoObjetivo) && $hatoObjetivo > 0) {
            $faltantes = max($hatoObjetivo - $evaluados, 0);
            $coberturaPct = $this->pct($evaluados, $hatoObjetivo);      // % evaluados del hato objetivo
            $faltantesPct = $this->pct($faltantes, $hatoObjetivo);
        }

        // Distribución por estados (tabla)
        $statusTable = [
            ['status' => 'implanted', 'count' => $implanted, 'pct' => $this->pct($implanted, $evaluados)],
            ['status' => 'pregnant',  'count' => $pregnant,  'pct' => $this->pct($pregnant,  $evaluados)],
            ['status' => 'discarded', 'count' => $discarded, 'pct' => $this->pct($discarded, $evaluados)],
        ];

        // Distribución de Work Team (por animal único)
        $workTeamDist = $this->distributionByField($rows, 'work_team', $evaluados);

        // Distribución de productos: parsea used_products_summary (por coma/; y cuenta por animal)
        $productsDist = $this->distributionProducts($rows, $evaluados);

        // Detalle
        $details = $rows->map(fn($r) => [
            'ultrasound_id'          => (int)$r->id,
            'control_bovine_id'      => (int)$r->control_bovine_id,
            'bovine_id'              => (int)$r->bovine_id,
            'serie'                  => $r->serie,
            'rgd'                    => $r->rgd,
            'property_id'            => $r->property_id ? (int)$r->property_id : null,
            'property_name'          => $r->property_name,
            'control_id'             => $r->control_id ? (int)$r->control_id : null,

            'date'                   => $r->date,
            'status'                 => $r->status,
            'tenfo_vitamins'         => (int)$r->vitamins_and_minerals === 1,
            'work_team'              => $r->work_team,
            'used_products_summary'  => $r->used_products_summary,
            'protocol_details'       => $r->protocol_details,
            'regufo'                 => $r->regufo,
        ])->values();

        return [
            'filters_applied' => $filters,

            'summary' => [
                // Cliente:
                'hato_evaluado' => ['count' => $evaluados, 'pct' => $coberturaPct], // pct vs hato objetivo si aplica
                'efectividad_real' => ['count' => $implanted, 'pct' => $efectividadRealPct], // trabajados=implantados

                'total_records' => $totalRecords,

                'implanted' => ['count' => $implanted, 'pct' => $this->pct($implanted, $evaluados)],
                'refugos'   => ['count' => $refugos,   'pct' => $this->pct($refugos,   $evaluados)],
                'pregnant'  => ['count' => $pregnant,  'pct' => $this->pct($pregnant,  $evaluados)],
                'discarded' => ['count' => $discarded, 'pct' => $this->pct($discarded, $evaluados)],

                'faltantes' => ['count' => $faltantes, 'pct' => $faltantesPct],
                'hato_objetivo' => $hatoObjetivo,

                'with_vitamins_and_minerals' => ['count' => $withVitamins, 'pct' => $this->pct($withVitamins, $evaluados)],
            ],

            'status_table' => $statusTable,

            'distributions' => [
                'work_team' => $workTeamDist,
                'products'  => $productsDist,
            ],

            'details' => $details,
        ];
    }

    private function distributionProducts(Collection $rows, int $animalsTotal): array
    {
        // Contaremos productos por animal único (si un animal tiene "A, B", cuenta 1 a A y 1 a B)
        $map = []; // product => set(control_bovine_id)

        foreach ($rows as $r) {
            $animalId = (int)$r->control_bovine_id;
            $raw = (string)($r->used_products_summary ?? '');

            $products = $this->parseProducts($raw);
            if (!$products) continue;

            foreach ($products as $p) {
                if (!isset($map[$p])) $map[$p] = [];
                $map[$p][$animalId] = true;
            }
        }

        // Armar salida ordenada
        $out = [];
        foreach ($map as $product => $set) {
            $cnt = count($set);
            $out[] = [
                'name'  => $product,
                'count' => $cnt,
                'pct'   => $this->pct($cnt, $animalsTotal),
            ];
        }

        usort($out, fn($a, $b) => $b['count'] <=> $a['count']);

        return $out;
    }

    private function parseProducts(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') return [];

        // Separadores típicos: coma, punto y coma, salto de línea
        $parts = preg_split('/[,\n;]+/', $raw);

        $clean = [];
        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '') continue;
            $clean[] = $p;
        }

        // únicos
        return array_values(array_unique($clean));
    }
}
