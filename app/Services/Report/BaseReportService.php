<?php

namespace App\Services\Report;

use Illuminate\Support\Collection;

abstract class BaseReportService
{
    protected function pct(int $num, int $den): float
    {
        if ($den <= 0) return 0.0;
        return round(($num / $den) * 100, 2);
    }

    protected function notEmpty($v): bool
    {
        return !is_null($v) && trim((string)$v) !== '';
    }

    /**
     * Distribución por texto exacto (por animal único).
     */
    protected function distributionByField(Collection $rows, string $field, int $animalsTotal, string $idField = 'control_bovine_id'): array
    {
        $counts = $rows
            ->filter(fn($r) => $this->notEmpty($r->{$field}))
            ->groupBy(fn($r) => trim((string)$r->{$field}))
            ->map(fn($group) => $group->pluck($idField)->unique()->count())
            ->sortDesc();

        $out = [];
        foreach ($counts as $name => $cnt) {
            $out[] = [
                'name'  => $name,
                'count' => $cnt,
                'pct'   => $this->pct($cnt, $animalsTotal),
            ];
        }
        return $out;
    }

    /**
     * Validación común de fechas según filter_type.
     * Retorna null si OK, o array con error.
     */
    protected function validateDateFilters(array $filters): ?array
    {
        $type  = $filters['filter_type'] ?? 'none';
        $start = $filters['date_start'] ?? null;
        $end   = $filters['date_end'] ?? null;

        if ($type === 'from' && !$start) {
            return ['status' => 400, 'code' => 'FILTER_INVALID', 'message' => 'Falta date_start para filter_type=from.'];
        }
        if ($type === 'until' && !$end) {
            return ['status' => 400, 'code' => 'FILTER_INVALID', 'message' => 'Falta date_end para filter_type=until.'];
        }
        if ($type === 'range') {
            if (!$start || !$end) {
                return ['status' => 400, 'code' => 'FILTER_INVALID', 'message' => 'Para filter_type=range debes enviar date_start y date_end.'];
            }
            if (strtotime($start) > strtotime($end)) {
                return ['status' => 400, 'code' => 'FILTER_INVALID', 'message' => 'date_end debe ser mayor o igual a date_start.'];
            }
        }

        return null;
    }

    /**
     * Respuesta OK estándar
     */
    protected function ok(int $status, string $message, array $data): array
    {
        return [
            'status' => $status,
            'payload' => [
                'success' => true,
                'message' => $message,
                'data' => $data,
                'errors' => null,
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                ],
            ],
        ];
    }

    /**
     * Respuesta FAIL estándar
     */
    protected function fail(int $status, string $message, string $code, $extra = null): array
    {
        return [
            'status' => $status,
            'payload' => [
                'success' => false,
                'message' => $message,
                'data' => null,
                'errors' => array_filter([
                    'code' => $code,
                    'extra' => $extra,
                ]),
                'meta' => [
                    'generated_at' => now()->toIso8601String(),
                ],
            ],
        ];
    }
}
