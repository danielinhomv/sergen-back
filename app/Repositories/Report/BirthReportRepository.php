<?php

namespace App\Repositories\Report;

use App\Models\Birth;
use App\Models\Control_bovine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BirthReportRepository
{
    public function fetchRows(array $filters): Collection
    {
        // births.bovine_id = ternero
        // births.control_bovine_id = madre en control_bovines
        $q = Birth::query()
            ->select([
                'births.id',
                'births.type_of_birth',
                'births.bovine_id as calf_id',
                'births.control_bovine_id',

                'cb.control_id',
                'cb.bovine_id as mother_bovine_id',

                'mother.serie as mother_serie',
                'mother.rgd as mother_rgd',
                'mother.property_id as mother_property_id',
                'p.name as property_name',

                // ternero (puede ser null si abort)
                'calf.serie as calf_serie',
                'calf.rgd as calf_rgd',
                'calf.sex as calf_sex',
                'calf.birthdate as birth_date',
                'calf.weight as calf_weight',
            ])
            ->join('control_bovines as cb', 'cb.id', '=', 'births.control_bovine_id')
            ->join('bovines as mother', 'mother.id', '=', 'cb.bovine_id')
            ->leftJoin('properties as p', 'p.id', '=', 'mother.property_id')
            ->leftJoin('bovines as calf', 'calf.id', '=', 'births.bovine_id');

        $this->applyCommonFilters($q, $filters);

        // Ordenamos por fecha del ternero; si es abort y no hay ternero, cae al final
        return $q->orderByDesc('birth_date')->get();
    }

    public function countHatoObjetivo(array $filters): ?int
    {
        // Para “hato objetivo” en Parto, solo tiene sentido si hay control_id
        if (empty($filters['control_id'])) return null;

        $q = Control_bovine::query()
            ->join('bovines', 'bovines.id', '=', 'control_bovines.bovine_id')
            ->where('control_bovines.control_id', $filters['control_id']);

        if (!empty($filters['property_id'])) {
            $q->where('bovines.property_id', $filters['property_id']);
        }

        return (int)$q->distinct('control_bovines.id')->count('control_bovines.id');
    }

    private function applyCommonFilters(Builder $q, array $filters): void
    {
        if (!empty($filters['property_id'])) {
            $q->where('mother.property_id', $filters['property_id']);
        }

        if (!empty($filters['control_id'])) {
            $q->where('cb.control_id', $filters['control_id']);
        }

        // Filtro fecha: usamos birth_date (calf.birthdate)
        $type  = $filters['filter_type'] ?? 'none';
        $start = $filters['date_start'] ?? null;
        $end   = $filters['date_end'] ?? null;

        if ($type === 'today') {
            $q->whereDate('calf.birthdate', now()->toDateString());
        } elseif ($type === 'from' && $start) {
            $q->whereDate('calf.birthdate', '>=', $start);
        } elseif ($type === 'until' && $end) {
            $q->whereDate('calf.birthdate', '<=', $end);
        } elseif ($type === 'range' && $start && $end) {
            $q->whereBetween('calf.birthdate', [$start, $end]);
        }

        // OJO: los abortos pueden no tener calf.birthdate (porque calf_id puede ser null)
        // Si quieres que abortos aparezcan aunque filtres por fecha, dímelo y te hago la condición OR.
    }
}
