<?php

namespace App\Services\Report\Strategies;

use App\Repositories\Report\ImplantRetrievalReportRepository;
use App\Services\Report\BaseReportService;
use App\Services\Report\Contracts\ReportStrategy;
use Illuminate\Support\Collection;

class ImplantRetrievalReportStrategy extends BaseReportService implements ReportStrategy
{
    public function __construct(
        private readonly ImplantRetrievalReportRepository $repo
    ) {}

    public function name(): string
    {
        return 'Reporte de Retiro de Implante';
    }

    public function validate(array $filters): ?array
    {
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

        $totalAnimals = $rows->pluck('control_bovine_id')->unique()->count();

        $retrieved = $rows->where('status', 'retrieved')
            ->pluck('control_bovine_id')->unique()->count();

        $lost = $rows->where('status', 'lost')
            ->pluck('control_bovine_id')->unique()->count();

        $faltantes = $faltantesPct = $coberturaPct = null;
        if (!is_null($hatoObjetivo) && $hatoObjetivo > 0) {
            $faltantes = max($hatoObjetivo - $totalAnimals, 0);
            $coberturaPct = $this->pct($totalAnimals, $hatoObjetivo);
            $faltantesPct = $this->pct($faltantes, $hatoObjetivo);
        }

        return [
            'filters_applied' => $filters,

            'summary' => [
                'total_animals_retrieved' => [
                    'count' => $retrieved,
                    'pct' => $this->pct($retrieved, $totalAnimals),
                ],
                'implant_losses' => [
                    'count' => $lost,
                    'pct' => $this->pct($lost, $totalAnimals),
                ],
                'total_animals_processed' => $totalAnimals,
                'hato_objetivo' => $hatoObjetivo,
                'cobertura_pct' => $coberturaPct,
                'faltantes' => [
                    'count' => $faltantes,
                    'pct' => $faltantesPct,
                ],
            ],

            'status_table' => [
                ['status' => 'retrieved', 'count' => $retrieved, 'pct' => $this->pct($retrieved, $totalAnimals)],
                ['status' => 'lost', 'count' => $lost, 'pct' => $this->pct($lost, $totalAnimals)],
            ],

            'distributions' => [
                'work_team' => $this->distributionByField($rows, 'work_team', $totalAnimals),
                'products' => $this->distributionProducts($rows, $totalAnimals),
            ],

            'details' => $rows->map(fn($r) => [
                'implant_retrieval_id' => (int)$r->id,
                'control_bovine_id' => (int)$r->control_bovine_id,
                'bovine_id' => (int)$r->bovine_id,
                'serie' => $r->serie,
                'rgd' => $r->rgd,
                'property_id' => $r->property_id,
                'property_name' => $r->property_name,
                'control_id' => $r->control_id,
                'date' => $r->date,
                'status' => $r->status,
                'work_team' => $r->work_team,
                'used_products_summary' => $r->used_products_summary,
            ])->values(),
        ];
    }

    private function distributionProducts(Collection $rows, int $animalsTotal): array
    {
        $map = [];

        foreach ($rows as $r) {
            $animalId = (int)$r->control_bovine_id;
            $raw = trim((string)$r->used_products_summary);
            if ($raw === '') continue;

            foreach (preg_split('/[,\n;]+/', $raw) as $p) {
                $p = trim($p);
                if ($p === '') continue;
                $map[$p][$animalId] = true;
            }
        }

        $out = [];
        foreach ($map as $product => $animals) {
            $cnt = count($animals);
            $out[] = [
                'name' => $product,
                'count' => $cnt,
                'pct' => $this->pct($cnt, $animalsTotal),
            ];
        }

        usort($out, fn($a, $b) => $b['count'] <=> $a['count']);
        return $out;
    }
}
