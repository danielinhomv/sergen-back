<?php

namespace App\Repositories\Report;

use App\Models\Insemination;

class InseminationReportRepository
{
    private array $reportMap = [
        'calidad_celo' => ['column' => 'heat_quality', 'type' => 'enum'],
        'body_condition_score' => ['column' => 'body_condition_score', 'type' => 'numeric'],
        'observacion' => ['column' => 'observation', 'type' => 'text'],
        'otros' => ['column' => 'others', 'type' => 'text'],
    ];

    private array $heatQualityMap = [
        'well' => 'well',
        'regular' => 'regular',
        'bad' => 'bad',
    ];

    public function processReport(
        array $parameters,
        ?string $startDate = null,
        ?string $endDate = null,
        $control_id
    ): array {
        // Inicia la consulta filtrando por el control_id a través de la relación Control_bovine
        $query = Insemination::whereHas('bovineControl', function ($query) use ($control_id) {
            $query->where('control_id', $control_id);
        });

        // La lógica para "todo" es manejada por la ausencia de otros parámetros de reporte
        if (empty($parameters)) {
            return $query->get()->toArray();
        }

        // Procesa cada parámetro para aplicar los filtros a la consulta
        foreach ($parameters as $type => $values) {
            if (array_key_exists($type, $this->reportMap)) {
                $columnName = $this->reportMap[$type]['column'];
                $dataType = $this->reportMap[$type]['type'];

                foreach ($values as $value) {
                    if ($dataType === 'numeric') {
                        // Verifica si el valor contiene un operador (>, >=)
                        preg_match('/^(>=|>)(.*)/', $value, $matches);
                        if (count($matches) > 0) {
                            $operator = $matches[1];
                            $cleanValue = (float)str_replace(',', '.', $matches[2]);
                        } else {
                            $operator = '=';
                            $cleanValue = (float)str_replace(',', '.', $value);
                        }
                        $query->where($columnName, $operator, $cleanValue);
                    } elseif ($dataType === 'text') {
                        // Para el tipo 'text', se asume que solo quieres los registros que tienen un valor
                        $query->whereNotNull($columnName);
                    } elseif ($dataType === 'enum') {
                        // Mapea el valor de la entidad al valor de la base de datos
                        $cleanValue = $this->heatQualityMap[$value] ?? null;
                        if ($cleanValue) {
                            $query->where($columnName, $cleanValue);
                        }
                    }
                }
            }
        }

        // Aplica el filtro de fechas
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        // Ejecuta la consulta y retorna el resultado
        return $query->get()->toArray();
    }
}
