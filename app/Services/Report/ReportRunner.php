<?php

namespace App\Services\Report;

use App\Services\Report\Contracts\ReportStrategy;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReportRunner extends BaseReportService
{
    public function run(ReportStrategy $strategy, array $filters): array
    {
        // 1) Validación común
        $dateError = $this->validateDateFilters($filters);
        if ($dateError) {
            return $this->fail($dateError['status'], $dateError['message'], $dateError['code']);
        }

        // 2) Validación específica del reporte
        $stageError = $strategy->validate($filters);
        if ($stageError) {
            return $this->fail(
                $stageError['status'] ?? 400,
                $stageError['message'] ?? 'Filtro inválido.',
                $stageError['code'] ?? 'STAGE_VALIDATION_ERROR',
                $stageError['extra'] ?? null
            );
        }

        // 3) Ejecución protegida
        try {
            $fetched = $strategy->fetch($filters);
            $data = $strategy->build($filters, $fetched);

            return $this->ok(200, $strategy->name() . ' generado correctamente.', $data);
        } catch (Throwable $e) {
            Log::error('ReportRunner failed', [
                'report' => $strategy->name(),
                'filters' => $filters,
                'error' => $e->getMessage(),
            ]);

            return $this->fail(
                500,
                'Error interno al generar el reporte.',
                'INTERNAL_SERVER_ERROR',
                app()->environment('local') ? ['details' => $e->getMessage()] : null
            );
        }
    }
}
