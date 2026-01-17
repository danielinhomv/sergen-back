<?php

namespace App\Services\Report\Strategies;

use App\Repositories\Report\GeneralPregnancyDiagnosisReportRepository;
use App\Services\Report\BaseReportService;
use App\Services\Report\Contracts\ReportStrategy;
use Illuminate\Support\Collection;

class GeneralPregnancyDiagnosisReportStrategy extends BaseReportService implements ReportStrategy
{
    public function __construct(
        private readonly GeneralPregnancyDiagnosisReportRepository $repo
    ) {
    }

    public function name(): string
    {
        return 'Diagnóstico de preñez general';
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

        $totalRecords = $rows->count();
        $totalAnimals = $rows->pluck('control_bovine_id')->unique()->count(); // diagnósticos (por animal)

        $pregnant = $this->countUniqueByStatus($rows, 'pregnant');
        $empty = $this->countUniqueByStatus($rows, 'empty');
        $discard = $this->countUniqueByStatus($rows, 'discard');
        $abort = $this->countUniqueByStatus($rows, 'abort');

        // % preñez (lo que pidió: “tasa confirmación” -> % preñez)
        $pregnancyPct = $this->pct($pregnant, $totalAnimals);

        // Faltantes vs hato objetivo (si hay control_id)
        // $faltantes = $faltantesPct = $coberturaPct = null;
        $faltantes = $faltantesPct = $coberturaPct = 0;
        if (!is_null($hatoObjetivo) && $hatoObjetivo > 0) {
            $faltantes = max($hatoObjetivo - $totalAnimals, 0);
            $coberturaPct = $this->pct($totalAnimals, $hatoObjetivo);
            $faltantesPct = $this->pct($faltantes, $hatoObjetivo);
        }

        // Preñez por toro (tasa = preñadas con toro X / total diagnosticadas con toro X)
        $pregByBull = $this->rateByGroup($rows, 'bull_name');

        // Preñez por celo (well/regular/bad -> bueno/regular/malo)
        $pregByHeat = $this->rateByHeatQuality($rows);

        $details = $rows->map(fn($r) => [
            'general_palpation_id' => (int) $r->id,
            'control_bovine_id' => (int) $r->control_bovine_id,
            'bovine_id' => (int) $r->bovine_id,
            'serie' => $r->serie,
            'rgd' => $r->rgd,
            'property_id' => $r->property_id ? (int) $r->property_id : null,
            'property_name' => $r->property_name,
            'control_id' => $r->control_id ? (int) $r->control_id : null,

            'date' => $r->date,
            'status' => $r->status, // pregnant/empty/discard/abort
            'observation' => $r->observation,

            // cruzado con IA
            'insemination_date' => $r->insemination_date,
            'bull_id' => $r->bull_id ? (int) $r->bull_id : null,
            'bull_name' => $r->bull_name,
            'heat_quality_raw' => $r->heat_quality,
            'heat_quality' => $this->heatLabel($r->heat_quality),
        ])->values();

        return [
            'filters_applied' => $filters,

            'summary' => [
                'total_diagnosticos' => $totalAnimals,
                'total_records' => $totalRecords,

                'pregnant' => ['count' => $pregnant, 'pct' => $this->pct($pregnant, $totalAnimals)],
                'empty' => ['count' => $empty, 'pct' => $this->pct($empty, $totalAnimals)],
                'discard' => ['count' => $discard, 'pct' => $this->pct($discard, $totalAnimals)],
                'abort' => ['count' => $abort, 'pct' => $this->pct($abort, $totalAnimals)],

                'pregnancy_pct' => $pregnancyPct,

                'hato_objetivo' => $hatoObjetivo,
                'cobertura_pct' => $coberturaPct,
                'faltantes' => ['count' => $faltantes, 'pct' => $faltantesPct],
            ],

            // Tabla de estados
            'status_table' => [
                ['status' => 'pregnant', 'count' => $pregnant, 'pct' => $this->pct($pregnant, $totalAnimals)],
                ['status' => 'empty', 'count' => $empty, 'pct' => $this->pct($empty, $totalAnimals)],
                ['status' => 'discard', 'count' => $discard, 'pct' => $this->pct($discard, $totalAnimals)],
                ['status' => 'abort', 'count' => $abort, 'pct' => $this->pct($abort, $totalAnimals)],
            ],

            'distributions' => [
                'pregnancy_by_bull' => $pregByBull,
                'pregnancy_by_heat_quality' => $pregByHeat,

                // no existen en esta tabla:
                'work_team' => [],
                'products' => [],
            ],

            'details' => $details,
        ];
    }

    private function countUniqueByStatus(Collection $rows, string $status): int
    {
        return $rows->where('status', $status)
            ->pluck('control_bovine_id')->unique()->count();
    }

    private function heatLabel(?string $raw): ?string
    {
        if (!$raw)
            return null;
        return match ($raw) {
            'well' => 'bueno',
            'regular' => 'regular',
            'bad' => 'malo',
            default => $raw,
        };
    }

    /**
     * Tasa por grupo: preñadas en el grupo / total diagnosticadas en el grupo
     */
    private function rateByGroup(Collection $rows, string $groupField): array
    {
        $totalByGroup = [];
        $pregByGroup = [];

        foreach ($rows as $r) {
            $animalId = (int) $r->control_bovine_id;
            $g = trim((string) ($r->{$groupField} ?? ''));
            if ($g === '')
                $g = 'SIN_DATO';

            $totalByGroup[$g][$animalId] = true;

            if ($r->status === 'pregnant') {
                $pregByGroup[$g][$animalId] = true;
            }
        }

        $out = [];
        foreach ($totalByGroup as $g => $setTotal) {
            $total = count($setTotal);
            $preg = isset($pregByGroup[$g]) ? count($pregByGroup[$g]) : 0;

            $out[] = [
                'name' => $g,
                'total' => $total,
                'pregnant' => $preg,
                'pregnancy_rate_pct' => $this->pct($preg, $total),
            ];
        }

        usort($out, fn($a, $b) => $b['pregnancy_rate_pct'] <=> $a['pregnancy_rate_pct']);
        return $out;
    }

    private function rateByHeatQuality(Collection $rows): array
    {
        $totalBy = [];
        $pregBy = [];

        foreach ($rows as $r) {
            $animalId = (int) $r->control_bovine_id;
            $label = $this->heatLabel($r->heat_quality);
            $label = $label ? trim($label) : 'SIN_DATO';

            $totalBy[$label][$animalId] = true;

            if ($r->status === 'pregnant') {
                $pregBy[$label][$animalId] = true;
            }
        }

        $out = [];
        foreach ($totalBy as $label => $setTotal) {
            $total = count($setTotal);
            $preg = isset($pregBy[$label]) ? count($pregBy[$label]) : 0;

            $out[] = [
                'name' => $label,
                'total' => $total,
                'pregnant' => $preg,
                'pregnancy_rate_pct' => $this->pct($preg, $total),
            ];
        }

        usort($out, fn($a, $b) => $b['pregnancy_rate_pct'] <=> $a['pregnancy_rate_pct']);
        return $out;
    }
}
