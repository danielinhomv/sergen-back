<?php

namespace App\Repositories\Report;

use Illuminate\Support\Facades\DB;

class DashboardGeneralReportRepository
{
    // ========= HATO OBJETIVO =========
    public function countHatoObjetivo(array $filters): int
    {
        $q = DB::table('control_bovines as cb')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        if (!empty($filters['control_id'])) {
            $q->where('cb.control_id', $filters['control_id']);
        }

        if (!empty($filters['property_id'])) {
            $q->where('b.property_id', $filters['property_id']);
        }

        return (int) $q->distinct('cb.id')->count('cb.id');
    }

    // ========= COUNTS POR ETAPA (por animal único) =========
    public function countStagePresync(array $filters): int
    {
        $q = DB::table('presincronizations as p')
            ->join('control_bovines as cb', 'cb.id', '=', 'p.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'p.application_date');

        return (int)$q->distinct('p.control_bovine_id')->count('p.control_bovine_id');
    }

    public function countStageUltrasound(array $filters): int
    {
        $q = DB::table('ultrasounds as u')
            ->join('control_bovines as cb', 'cb.id', '=', 'u.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'u.date');

        return (int)$q->distinct('u.control_bovine_id')->count('u.control_bovine_id');
    }

    public function ultrasoundStatusBreakdown(array $filters): array
    {
        $q = DB::table('ultrasounds as u')
            ->join('control_bovines as cb', 'cb.id', '=', 'u.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'u.date');

        $rows = $q->select('u.status', DB::raw('COUNT(DISTINCT u.control_bovine_id) as total'))
            ->groupBy('u.status')
            ->get();

        return $rows->map(fn($r) => ['status' => $r->status, 'count' => (int)$r->total])->toArray();
    }

    public function countStageImplantRetrieval(array $filters): int
    {
        $q = DB::table('implant_retrievals as ir')
            ->join('control_bovines as cb', 'cb.id', '=', 'ir.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'ir.date');

        return (int)$q->distinct('ir.control_bovine_id')->count('ir.control_bovine_id');
    }

    public function implantRetrievalStatusBreakdown(array $filters): array
    {
        $q = DB::table('implant_retrievals as ir')
            ->join('control_bovines as cb', 'cb.id', '=', 'ir.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'ir.date');

        $rows = $q->select('ir.status', DB::raw('COUNT(DISTINCT ir.control_bovine_id) as total'))
            ->groupBy('ir.status')
            ->get();

        return $rows->map(fn($r) => ['status' => $r->status, 'count' => (int)$r->total])->toArray();
    }

    public function countStageInsemination(array $filters): int
    {
        $q = DB::table('inseminations as i')
            ->join('control_bovines as cb', 'cb.id', '=', 'i.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'i.date');

        return (int)$q->distinct('i.control_bovine_id')->count('i.control_bovine_id');
    }

    public function inseminationHeatQualityBreakdown(array $filters): array
    {
        $q = DB::table('inseminations as i')
            ->join('control_bovines as cb', 'cb.id', '=', 'i.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'i.date');

        $rows = $q->select('i.heat_quality', DB::raw('COUNT(DISTINCT i.control_bovine_id) as total'))
            ->groupBy('i.heat_quality')
            ->get();

        return $rows->map(fn($r) => ['heat_quality' => $r->heat_quality, 'count' => (int)$r->total])->toArray();
    }

    public function countStageConfirmatory(array $filters): int
    {
        $q = DB::table('confirmatory_ultrasounds as cu')
            ->join('control_bovines as cb', 'cb.id', '=', 'cu.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'cu.date');

        return (int)$q->distinct('cu.control_bovine_id')->count('cu.control_bovine_id');
    }

    public function confirmatoryStatusBreakdown(array $filters): array
    {
        $q = DB::table('confirmatory_ultrasounds as cu')
            ->join('control_bovines as cb', 'cb.id', '=', 'cu.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'cu.date');

        $rows = $q->select('cu.status', DB::raw('COUNT(DISTINCT cu.control_bovine_id) as total'))
            ->groupBy('cu.status')
            ->get();

        return $rows->map(fn($r) => ['status' => $r->status, 'count' => (int)$r->total])->toArray();
    }

    public function countStageGeneralDiagnosis(array $filters): int
    {
        $q = DB::table('general_palpations as gp')
            ->join('control_bovines as cb', 'cb.id', '=', 'gp.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'gp.date');

        return (int)$q->distinct('gp.control_bovine_id')->count('gp.control_bovine_id');
    }

    public function generalDiagnosisStatusBreakdown(array $filters): array
    {
        $q = DB::table('general_palpations as gp')
            ->join('control_bovines as cb', 'cb.id', '=', 'gp.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'gp.date');

        $rows = $q->select('gp.status', DB::raw('COUNT(DISTINCT gp.control_bovine_id) as total'))
            ->groupBy('gp.status')
            ->get();

        return $rows->map(fn($r) => ['status' => $r->status, 'count' => (int)$r->total])->toArray();
    }

    public function countStageBirths(array $filters): int
    {
        // births no tiene fecha, usamos bovines.birthdate del ternero (births.bovine_id)
        $q = DB::table('births as br')
            ->join('control_bovines as cb', 'cb.id', '=', 'br.control_bovine_id')
            ->join('bovines as dam', 'dam.id', '=', 'cb.bovine_id')     // madre (para property_id)
            ->leftJoin('bovines as calf', 'calf.id', '=', 'br.bovine_id'); // ternero (fecha + sexo)

        $this->applyCommonBirths($q, $filters, 'calf.birthdate');

        return (int)$q->distinct('br.control_bovine_id')->count('br.control_bovine_id');
    }

    public function birthTypeBreakdown(array $filters): array
    {
        $q = DB::table('births as br')
            ->join('control_bovines as cb', 'cb.id', '=', 'br.control_bovine_id')
            ->join('bovines as dam', 'dam.id', '=', 'cb.bovine_id')
            ->leftJoin('bovines as calf', 'calf.id', '=', 'br.bovine_id');

        $this->applyCommonBirths($q, $filters, 'calf.birthdate');

        $rows = $q->select('br.type_of_birth', DB::raw('COUNT(DISTINCT br.control_bovine_id) as total'))
            ->groupBy('br.type_of_birth')
            ->get();

        return $rows->map(fn($r) => ['type_of_birth' => $r->type_of_birth, 'count' => (int)$r->total])->toArray();
    }

    public function calfSexBreakdown(array $filters): array
    {
        $q = DB::table('births as br')
            ->join('control_bovines as cb', 'cb.id', '=', 'br.control_bovine_id')
            ->join('bovines as dam', 'dam.id', '=', 'cb.bovine_id')
            ->leftJoin('bovines as calf', 'calf.id', '=', 'br.bovine_id');

        $this->applyCommonBirths($q, $filters, 'calf.birthdate');

        $rows = $q->whereNotNull('calf.sex')
            ->select('calf.sex', DB::raw('COUNT(*) as total'))
            ->groupBy('calf.sex')
            ->get();

        return $rows->map(fn($r) => ['sex' => $r->sex, 'count' => (int)$r->total])->toArray();
    }

    // ========= SERIES (línea/barras) =========
    public function seriesInseminations(array $filters): array
    {
        $q = DB::table('inseminations as i')
            ->join('control_bovines as cb', 'cb.id', '=', 'i.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id');

        $this->applyCommon($q, $filters, 'i.date');

        return $this->groupSeries($q, $filters['group_by'] ?? 'week', 'i.date', 'i.control_bovine_id');
    }

    public function seriesPregnanciesGeneral(array $filters): array
    {
        $q = DB::table('general_palpations as gp')
            ->join('control_bovines as cb', 'cb.id', '=', 'gp.control_bovine_id')
            ->join('bovines as b', 'b.id', '=', 'cb.bovine_id')
            ->where('gp.status', 'pregnant');

        $this->applyCommon($q, $filters, 'gp.date');

        return $this->groupSeries($q, $filters['group_by'] ?? 'week', 'gp.date', 'gp.control_bovine_id');
    }

    // ========= HELPERS =========
    private function applyCommon($q, array $filters, string $dateColumn): void
    {
        if (!empty($filters['control_id'])) {
            $q->where('cb.control_id', $filters['control_id']);
        }
        if (!empty($filters['property_id'])) {
            $q->where('b.property_id', $filters['property_id']);
        }

        $this->applyDateFilter($q, $filters, $dateColumn);
    }

    private function applyCommonBirths($q, array $filters, string $dateColumn): void
    {
        if (!empty($filters['control_id'])) {
            $q->where('cb.control_id', $filters['control_id']);
        }
        if (!empty($filters['property_id'])) {
            $q->where('dam.property_id', $filters['property_id']); // propiedad de la madre
        }

        $this->applyDateFilter($q, $filters, $dateColumn);
    }

    private function applyDateFilter($q, array $filters, string $dateColumn): void
    {
        $type = $filters['filter_type'] ?? 'none';
        $start = $filters['date_start'] ?? null;
        $end = $filters['date_end'] ?? null;

        if ($type === 'today') {
            $q->whereDate($dateColumn, now()->toDateString());
            return;
        }
        if ($type === 'from' && $start) {
            $q->whereDate($dateColumn, '>=', $start);
            return;
        }
        if ($type === 'until' && $end) {
            $q->whereDate($dateColumn, '<=', $end);
            return;
        }
        if ($type === 'range' && $start && $end) {
            $q->whereBetween($dateColumn, [$start, $end]);
        }
    }

    private function groupSeries($q, string $groupBy, string $dateColumn, string $distinctIdColumn): array
    {
        // labels: week => YYYY-WW, month => YYYY-MM, year => YYYY
        if ($groupBy === 'year') {
            $labelExpr = DB::raw("YEAR($dateColumn) as label");
            $groupExpr = DB::raw("YEAR($dateColumn)");
        } elseif ($groupBy === 'month') {
            $labelExpr = DB::raw("DATE_FORMAT($dateColumn, '%Y-%m') as label");
            $groupExpr = DB::raw("DATE_FORMAT($dateColumn, '%Y-%m')");
        } else {
            // week ISO-ish - AGRUPA POR LA EXPRESIÓN COMPLETA
            $labelExpr = DB::raw("CONCAT(YEAR($dateColumn), '-W', LPAD(WEEK($dateColumn, 3), 2, '0')) as label");
            // $groupExpr = DB::raw("YEAR($dateColumn), WEEK($dateColumn, 3)");
            $groupExpr = DB::raw("CONCAT(YEAR($dateColumn), '-W', LPAD(WEEK($dateColumn, 3), 2, '0'))");
        }

        $rows = $q->select($labelExpr, DB::raw("COUNT(DISTINCT $distinctIdColumn) as total"))
            ->groupBy($groupExpr)
            ->orderBy('label')
            ->get();

        return $rows->map(fn($r) => ['label' => (string)$r->label, 'count' => (int)$r->total])->toArray();
    }
}
