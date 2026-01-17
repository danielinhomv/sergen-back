<?php

namespace App\Services\Report\Strategies;

use App\Repositories\Report\BirthReportRepository;
use App\Services\Report\BaseReportService;
use App\Services\Report\Contracts\ReportStrategy;
use Illuminate\Support\Collection;

class BirthReportStrategy extends BaseReportService implements ReportStrategy
{
    public function __construct(
        private readonly BirthReportRepository $repo
    ) {
    }

    public function name(): string
    {
        return 'Reporte de Parto';
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

        // Madres con registro de parto (únicas)
        $mothersWithBirth = $rows->pluck('control_bovine_id')->unique()->count();
        $totalRecords = $rows->count();

        // Distribución por tipo de parto
        $typeDist = $this->distributionByField($rows, 'type_of_birth', $mothersWithBirth, 'control_bovine_id');

        // Conteos por tipo (por madre única)
        $normal = $this->countUniqueByType($rows, 'normal');
        $premeture = $this->countUniqueByType($rows, 'premeture');
        $stillbirth = $this->countUniqueByType($rows, 'stillbirth');
        $abort = $this->countUniqueByType($rows, 'abort');

        // Sexos del ternero (solo donde hay ternero y tiene sex)
        $calfRows = $rows->filter(fn($r) => $this->notEmpty($r->calf_sex));
        $calvesTotal = $calfRows->count(); // registros con sexo disponible (puede incluir stillbirth)
        $male = $calfRows->where('calf_sex', 'male')->count();
        $female = $calfRows->where('calf_sex', 'female')->count();

        // Faltantes vs hato objetivo (si hay control_id)
        // $faltantes = $faltantesPct = $coberturaPct = null;
        $faltantes = $faltantesPct = $coberturaPct = 0;
        if (!is_null($hatoObjetivo) && $hatoObjetivo > 0) {
            $faltantes = max($hatoObjetivo - $mothersWithBirth, 0);
            $coberturaPct = $this->pct($mothersWithBirth, $hatoObjetivo);
            $faltantesPct = $this->pct($faltantes, $hatoObjetivo);
        }

        // “Separar al hijo…”:
        // Con esta BD no hay un campo directo de “apta para destetar / apta para nuevo servicio”.
        // Dejamos un bloque informativo en el detalle para que el front pueda marcarlo si luego agregas esa lógica.
        $details = $rows->map(fn($r) => [
            'birth_id' => (int) $r->id,
            'control_bovine_id' => (int) $r->control_bovine_id,
            'control_id' => $r->control_id ? (int) $r->control_id : null,

            'property_id' => $r->mother_property_id ? (int) $r->mother_property_id : null,
            'property_name' => $r->property_name,

            'type_of_birth' => $r->type_of_birth,

            'mother_bovine_id' => $r->mother_bovine_id ? (int) $r->mother_bovine_id : null,
            'mother_serie' => $r->mother_serie,
            'mother_rgd' => $r->mother_rgd,

            'calf_id' => $r->calf_id ? (int) $r->calf_id : null,
            'calf_serie' => $r->calf_serie,
            'calf_rgd' => $r->calf_rgd,
            'calf_sex' => $r->calf_sex,
            'birth_date' => $r->birth_date,
            'calf_weight' => $r->calf_weight,

            // Placeholder de negocio:
            'weaning_separation_rule' => 'Pendiente: depende de si la madre está apta para destete/nuevo servicio (no hay campo en BD actual).',
        ])->values();

        return [
            'filters_applied' => $filters,

            'summary' => [
                'total_records' => $totalRecords,
                'total_mothers_with_birth' => $mothersWithBirth,

                'hato_objetivo' => $hatoObjetivo,
                'cobertura_pct' => $coberturaPct,
                'faltantes' => ['count' => $faltantes, 'pct' => $faltantesPct],

                // Tipos de parto
                'normal' => ['count' => $normal, 'pct' => $this->pct($normal, $mothersWithBirth)],
                'premeture' => ['count' => $premeture, 'pct' => $this->pct($premeture, $mothersWithBirth)],
                'stillbirth' => ['count' => $stillbirth, 'pct' => $this->pct($stillbirth, $mothersWithBirth)],
                'abort' => ['count' => $abort, 'pct' => $this->pct($abort, $mothersWithBirth)],

                // Sexos (sobre registros que sí tienen calf_sex)
                'calves_with_sex_data' => $calvesTotal,
                'male_calves' => ['count' => $male, 'pct' => $this->pct($male, $calvesTotal)],
                'female_calves' => ['count' => $female, 'pct' => $this->pct($female, $calvesTotal)],
            ],

            'distributions' => [
                'type_of_birth' => $typeDist,
                'calf_sex' => [
                    ['name' => 'male', 'count' => $male, 'pct' => $this->pct($male, $calvesTotal)],
                    ['name' => 'female', 'count' => $female, 'pct' => $this->pct($female, $calvesTotal)],
                ],
            ],

            'details' => $details,
        ];
    }

    private function countUniqueByType(Collection $rows, string $type): int
    {
        return $rows->where('type_of_birth', $type)
            ->pluck('control_bovine_id')->unique()->count();
    }
}
