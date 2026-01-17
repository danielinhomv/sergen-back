<?php

namespace App\Repositories\Report;

use App\Models\Bovine;
use App\Models\Ultrasound;
use App\Models\Control_bovine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UltrasoundReportRepository
{
    public function fetchRows(array $filters): Collection
    {
        $q = Ultrasound::query()
            ->select([
                'ultrasounds.id',
                'ultrasounds.control_bovine_id',
                'ultrasounds.status',
                'ultrasounds.vitamins_and_minerals',
                'ultrasounds.protocol_details',
                'ultrasounds.regufo',
                'ultrasounds.used_products_summary',
                'ultrasounds.work_team',
                'ultrasounds.date',

                'control_bovines.control_id',
                'control_bovines.bovine_id',

                'bovines.serie',
                'bovines.rgd',
                'bovines.property_id',

                'properties.name as property_name',
            ])
            ->join('control_bovines', 'control_bovines.id', '=', 'ultrasounds.control_bovine_id')
            ->join('bovines', 'bovines.id', '=', 'control_bovines.bovine_id')
            ->leftJoin('properties', 'properties.id', '=', 'bovines.property_id');

        $this->applyCommonFilters($q, $filters);

        return $q->orderByDesc('ultrasounds.date')->get();
    }

    public function countHatoObjetivo(array $filters): ?int
    {
        // if (empty($filters['control_id'])) {
        //     return null;
        // }

        // $q = Control_bovine::query()
        //     ->join('bovines', 'bovines.id', '=', 'control_bovines.bovine_id')
        //     ->where('control_bovines.control_id', $filters['control_id']);

        $q = Bovine::query()
            // ->leftJoin('control_bovines', 'control_bovines.bovine_id', '=', 'bovines.id')
            ->where('bovines.sex', 'female');

        if (!empty($filters['property_id'])) {
            $q->where('bovines.property_id', $filters['property_id']);
        }

        // return (int) $q->distinct('control_bovines.id')->count('control_bovines.id');
        return (int) $q->count();
    }

    private function applyCommonFilters(Builder $q, array $filters): void
    {
        if (!empty($filters['property_id'])) {
            $q->where('bovines.property_id', $filters['property_id']);
        }

        if (!empty($filters['control_id'])) {
            $q->where('control_bovines.control_id', $filters['control_id']);
        }

        $type  = $filters['filter_type'] ?? 'none';
        $start = $filters['date_start'] ?? null;
        $end   = $filters['date_end'] ?? null;

        if ($type === 'today') {
            $q->whereDate('ultrasounds.date', now()->toDateString());
            return;
        }

        if ($type === 'from' && $start) {
            $q->whereDate('ultrasounds.date', '>=', $start);
            return;
        }

        if ($type === 'until' && $end) {
            $q->whereDate('ultrasounds.date', '<=', $end);
            return;
        }

        if ($type === 'range' && $start && $end) {
            $q->whereBetween('ultrasounds.date', [
                $start . ' 00:00:00',
                $end . ' 23:59:59',
            ]);
        }
    }
}
