<?php

namespace App\Services\Report\Strategies;

use App\Repositories\Report\PresynchronizationReportRepository;
use App\Services\Report\BaseReportService;
use App\Services\Report\Contracts\ReportStrategy;

class PresynchronizationReportStrategy extends BaseReportService implements ReportStrategy
{
    public function __construct(
        private readonly PresynchronizationReportRepository $repo
    ) {}

    public function name(): string
    {
        return 'Reporte de Presincronización';
    }

    public function validate(array $filters): ?array
    {
        // Validación extra (opcional):
        // Si filter_type=range, validateDateFilters ya lo hizo el runner.
        // Puedes agregar cosas del negocio aquí si deseas.
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
        $rows = $fetched['rows'];
        $hatoObjetivo = $fetched['hato_objetivo'];

        $totalRecords = $rows->count();
        $totalAnimals = $rows->pluck('control_bovine_id')->unique()->count();

        $withVaccine  = $rows->filter(fn($r) => $this->notEmpty($r->reproductive_vaccine))->count();
        $withSincro   = $rows->filter(fn($r) => $this->notEmpty($r->sincrogest_product))->count();
        $withAnti     = $rows->filter(fn($r) => $this->notEmpty($r->antiparasitic_product))->count();
        $withVitamins = $rows->filter(fn($r) => (int)$r->vitamins_and_minerals === 1)->count();

        // $coberturaPct = $faltantes = $faltantesPct = null;
        $coberturaPct = $faltantes = $faltantesPct = 0;

        if (!is_null($hatoObjetivo) && $hatoObjetivo > 0) {
            $faltantes = max($hatoObjetivo - $totalAnimals, 0);
            $coberturaPct = $this->pct($totalAnimals, $hatoObjetivo);
            $faltantesPct = $this->pct($faltantes, $hatoObjetivo);
        }

        return [
            'filters_applied' => $filters,

            'summary' => [
                'total_records' => $totalRecords,
                'total_animals' => $totalAnimals,

                'hato_objetivo' => $hatoObjetivo,
                'cobertura_pct' => $coberturaPct ,
                'faltantes' => $faltantes,
                'faltantes_pct' => $faltantesPct,

                'with_reproductive_vaccine' => [
                    'count' => $withVaccine,
                    'pct' => $this->pct($withVaccine, $totalAnimals),
                ],
                'with_sincrogest_product' => [
                    'count' => $withSincro,
                    'pct' => $this->pct($withSincro, $totalAnimals),
                ],
                'with_antiparasitic_product' => [
                    'count' => $withAnti,
                    'pct' => $this->pct($withAnti, $totalAnimals),
                ],
                'with_vitamins_and_minerals' => [
                    'count' => $withVitamins,
                    'pct' => $this->pct($withVitamins, $totalAnimals),
                ],
            ],

            'distributions' => [
                'reproductive_vaccine' => $this->distributionByField($rows, 'reproductive_vaccine', $totalAnimals),
                'sincrogest_product' => $this->distributionByField($rows, 'sincrogest_product', $totalAnimals),
                'antiparasitic_product' => $this->distributionByField($rows, 'antiparasitic_product', $totalAnimals),
            ],

            'details' => $rows->map(fn($r) => [
                'control_bovine_id'     => (int)$r->control_bovine_id,
                'bovine_id'             => (int)$r->bovine_id,
                'serie'                 => $r->serie,
                'rgd'                   => $r->rgd,
                'property_id'           => $r->property_id ? (int)$r->property_id : null,
                'property_name'         => $r->property_name,
                'control_id'            => $r->control_id ? (int)$r->control_id : null,

                'application_date'      => $r->application_date,
                'reproductive_vaccine'  => $r->reproductive_vaccine,
                'sincrogest_product'    => $r->sincrogest_product,
                'antiparasitic_product' => $r->antiparasitic_product,
                'vitamins_and_minerals' => (int)$r->vitamins_and_minerals === 1,
            ])->values(),
        ];
    }
}
