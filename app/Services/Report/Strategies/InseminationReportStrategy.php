<?php

namespace App\Services\Report\Strategies;

use App\Repositories\Report\InseminationReportRepository;
use App\Services\Report\BaseReportService;
use App\Services\Report\Contracts\ReportStrategy;
use Illuminate\Support\Collection;

class InseminationReportStrategy extends BaseReportService implements ReportStrategy
{
    public function __construct(
        private readonly InseminationReportRepository $repo
    ) {}

    public function name(): string
    {
        return 'Reporte de Inseminación';
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

        $inseminatedAnimals = $rows->pluck('control_bovine_id')->unique()->count();
        $totalRecords = $rows->count();

        $faltantes = $faltantesPct = $coberturaPct = null;
        if (!is_null($hatoObjetivo) && $hatoObjetivo > 0) {
            $faltantes = max($hatoObjetivo - $inseminatedAnimals, 0);
            $coberturaPct = $this->pct($inseminatedAnimals, $hatoObjetivo);
            $faltantesPct = $this->pct($faltantes, $hatoObjetivo);
        }

        // Distribución por toro (uso por toro en fase 4)
        $bullDist = $this->distributionByField($rows, 'bull_name', $inseminatedAnimals);

        // Distribución por celo (normalizado)
        $heatDist = $this->heatQualityDistribution($rows, $inseminatedAnimals);

        // % concepción (en fase 4 lo interpretamos como cobertura de IA vs objetivo)
        $conceptionPct = !is_null($coberturaPct) ? $coberturaPct : 100.0;

        $details = $rows->map(fn($r) => [
            'insemination_id' => (int)$r->id,
            'control_bovine_id' => (int)$r->control_bovine_id,
            'bovine_id' => (int)$r->bovine_id,
            'serie' => $r->serie,
            'rgd' => $r->rgd,
            'property_id' => $r->property_id ? (int)$r->property_id : null,
            'property_name' => $r->property_name,
            'control_id' => $r->control_id ? (int)$r->control_id : null,

            'date' => $r->date,
            'bull_id' => (int)$r->bull_id,
            'bull_name' => $r->bull_name,

            // raw y label
            'heat_quality_raw' => $r->heat_quality,
            'heat_quality' => $this->heatLabel($r->heat_quality),

            'body_condition_score' => (float)$r->body_condition_score,
            'observation' => $r->observation,
            'others' => $r->others,
        ])->values();

        return [
            'filters_applied' => $filters,

            'summary' => [
                'total_records' => $totalRecords,
                'total_animals_inseminated' => $inseminatedAnimals,

                'hato_objetivo' => $hatoObjetivo,
                'cobertura_pct' => $coberturaPct,
                'faltantes' => ['count' => $faltantes, 'pct' => $faltantesPct],

                'conception_pct' => $conceptionPct,
            ],

            'distributions' => [
                'bulls' => $bullDist,
                'heat_quality' => $heatDist,
            ],

            'details' => $details
        ];
    }

    private function heatLabel(?string $raw): ?string
    {
        if (!$raw) return null;
        return match ($raw) {
            'well' => 'bueno',
            'regular' => 'regular',
            'bad' => 'malo',
            default => $raw,
        };
    }

    private function heatQualityDistribution(Collection $rows, int $animalsTotal): array
    {
        $map = [
            'bueno' => [],
            'regular' => [],
            'malo' => [],
        ];

        foreach ($rows as $r) {
            $animalId = (int)$r->control_bovine_id;
            $label = $this->heatLabel($r->heat_quality);
            if (!$label) continue;
            if (!isset($map[$label])) $map[$label] = [];
            $map[$label][$animalId] = true;
        }

        $out = [];
        foreach ($map as $label => $set) {
            $cnt = count($set);
            $out[] = ['name' => $label, 'count' => $cnt, 'pct' => $this->pct($cnt, $animalsTotal)];
        }

        usort($out, fn($a, $b) => $b['count'] <=> $a['count']);
        return $out;
    }
}
