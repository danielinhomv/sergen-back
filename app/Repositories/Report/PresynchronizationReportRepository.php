<?php

namespace App\Repositories\Report;

use App\Models\Presincronization;
// use App\Models\Control_bovine;
use App\Models\Bovine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PresynchronizationReportRepository
{
    public function fetchRows(array $filters): Collection
    {
        $q = Presincronization::query()
            ->select([
                'presincronizations.id',
                'presincronizations.control_bovine_id',
                'presincronizations.application_date',
                'presincronizations.reproductive_vaccine',
                'presincronizations.sincrogest_product',
                'presincronizations.antiparasitic_product',
                'presincronizations.vitamins_and_minerals',

                'control_bovines.control_id',
                'control_bovines.bovine_id',

                'bovines.serie',
                'bovines.rgd',
                'bovines.property_id',

                'properties.name as property_name',
            ])
            ->join('control_bovines', 'control_bovines.id', '=', 'presincronizations.control_bovine_id')
            ->join('bovines', 'bovines.id', '=', 'control_bovines.bovine_id')
            ->leftJoin('properties', 'properties.id', '=', 'bovines.property_id');

        $this->applyCommonFilters($q, $filters);

        return $q->orderByDesc('presincronizations.application_date')->get();
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
            ->where('bovines.sex','female');

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
            $q->whereDate('presincronizations.application_date', now()->toDateString());
            return;
        }

        if ($type === 'from' && $start) {
            $q->whereDate('presincronizations.application_date', '>=', $start);
            return;
        }

        if ($type === 'until' && $end) {
            $q->whereDate('presincronizations.application_date', '<=', $end);
            return;
        }

        if ($type === 'range' && $start && $end) {
            $q->whereBetween('presincronizations.application_date', [
                $start . ' 00:00:00',
                $end . ' 23:59:59',
            ]);
        }
    }
}
