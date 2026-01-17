<?php

namespace App\Repositories\Report;

use App\Models\Control_bovine;
use App\Models\General_palpation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GeneralPregnancyDiagnosisReportRepository
{
    public function fetchRows(array $filters): Collection
    {
        // Última inseminación por animal (control_bovine_id)
        $lastInsemination = DB::table('inseminations as i')
            ->select([
                'i.control_bovine_id',
                'i.bull_id',
                'i.heat_quality',
                'i.date as insemination_date',
            ])
            ->join(DB::raw('(SELECT control_bovine_id, MAX(date) as max_date FROM inseminations GROUP BY control_bovine_id) last_i'), function ($join) {
                $join->on('last_i.control_bovine_id', '=', 'i.control_bovine_id')
                    ->on('last_i.max_date', '=', 'i.date');
            });

        $q = General_palpation::query()
            ->select([
                'general_palpations.id',
                'general_palpations.control_bovine_id',
                'general_palpations.status',      // pregnant | empty | discard | abort
                'general_palpations.observation',
                'general_palpations.date',

                'control_bovines.control_id',
                'control_bovines.bovine_id',

                'bovines.serie',
                'bovines.rgd',
                'bovines.property_id',
                'properties.name as property_name',

                // Cruzado con IA
                'li.bull_id',
                'bulls.name as bull_name',
                'li.heat_quality',
                'li.insemination_date',
            ])
            ->join('control_bovines', 'control_bovines.id', '=', 'general_palpations.control_bovine_id')
            ->join('bovines', 'bovines.id', '=', 'control_bovines.bovine_id')
            ->leftJoin('properties', 'properties.id', '=', 'bovines.property_id')
            ->leftJoinSub($lastInsemination, 'li', function ($join) {
                $join->on('li.control_bovine_id', '=', 'general_palpations.control_bovine_id');
            })
            ->leftJoin('bulls', 'bulls.id', '=', 'li.bull_id');

        $this->applyCommonFilters($q, $filters);

        return $q->orderByDesc('general_palpations.date')->get();
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
        $end   = $filters['date_end'] ?? null;

        if ($type === 'today') {
            $q->whereDate('general_palpations.date', now()->toDateString());
        } elseif ($type === 'from' && $start) {
            $q->whereDate('general_palpations.date', '>=', $start);
        } elseif ($type === 'until' && $end) {
            $q->whereDate('general_palpations.date', '<=', $end);
        } elseif ($type === 'range' && $start && $end) {
            $q->whereBetween('general_palpations.date', [$start, $end]); // date es DATE
        }
    }
}
