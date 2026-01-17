<?php

namespace App\Repositories\Report;

use App\Models\Control_bovine;
use App\Models\Implant_retrieval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ImplantRetrievalReportRepository
{
    public function fetchRows(array $filters): Collection
    {
        $q = Implant_retrieval::query()
            ->select([
                'implant_retrievals.id',
                'implant_retrievals.control_bovine_id',
                'implant_retrievals.status', // retrieved | lost
                'implant_retrievals.used_products_summary',
                'implant_retrievals.work_team',
                'implant_retrievals.date',

                'control_bovines.control_id',
                'control_bovines.bovine_id',

                'bovines.serie',
                'bovines.rgd',
                'bovines.property_id',

                'properties.name as property_name',
            ])
            ->join('control_bovines', 'control_bovines.id', '=', 'implant_retrievals.control_bovine_id')
            ->join('bovines', 'bovines.id', '=', 'control_bovines.bovine_id')
            ->leftJoin('properties', 'properties.id', '=', 'bovines.property_id');

        $this->applyCommonFilters($q, $filters);

        return $q->orderByDesc('implant_retrievals.date')->get();
    }

    public function countHatoObjetivo(array $filters): ?int
    {
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
            $q->where('bovines.property_id', $filters['property_id']);
        }

        if (!empty($filters['control_id'])) {
            $q->where('control_bovines.control_id', $filters['control_id']);
        }

        $type = $filters['filter_type'] ?? 'none';
        $start = $filters['date_start'] ?? null;
        $end = $filters['date_end'] ?? null;

        if ($type === 'today') {
            $q->whereDate('implant_retrievals.date', now()->toDateString());
        } elseif ($type === 'from' && $start) {
            $q->whereDate('implant_retrievals.date', '>=', $start);
        } elseif ($type === 'until' && $end) {
            $q->whereDate('implant_retrievals.date', '<=', $end);
        } elseif ($type === 'range' && $start && $end) {
            $q->whereBetween('implant_retrievals.date', [
                $start . ' 00:00:00',
                $end . ' 23:59:59',
            ]);
        }
    }
}
