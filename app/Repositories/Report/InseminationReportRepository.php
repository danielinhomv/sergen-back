<?php

namespace App\Repositories\Report;

use App\Models\Control;
use App\Models\Current_session;
use App\Models\Insemination;
use Illuminate\Support\Facades\Log;

class InseminationReportRepository
{
    private array $reportMap = [
        'heat_quality' => ['column' => 'heat_quality', 'type' => 'enum'],
        'body_condition_score' => ['column' => 'body_condition_score', 'type' => 'numeric'],
        'observation' => ['column' => 'observation', 'type' => 'text_observation'],
        'others' => ['column' => 'others', 'type' => 'text_others'],
    ];

    private array $heatQualityMap = [
        'well' => 'well',
        'regular' => 'regular',
        'bad' => 'bad',
    ];

    public function processReport(
        array $parameters,
        int $property_id,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {

        $currentSession = Current_session::where('property_id', $property_id)->first();

        if ($currentSession === null) {
            return ['error' => 'No se encontró la propiedad current_session.'];
        }

        if (!$currentSession->isActive()) {
            return ['error' => 'La propiedad no está activa.'];
        }

        $protocolo = Control::where('property_id', $property_id)->first();
        if (!$protocolo) {
            return ['error' => 'No se inició un control de protocolo en la propiedad.'];
        }

        $protocolo_id = $protocolo->id;

        // Query base
        $query = Insemination::whereHas('control_bovine', function ($q) use ($protocolo_id) {
            $q->where('control_id', $protocolo_id);
        });

        Log::info('Parámetros recibidos:', $parameters);

        // Filtrar solo los parámetros que tienen valor (no vacíos)
        $validParameters = array_filter($parameters, function($value) {
            return $value !== '' && $value !== null;
        });

        Log::info('Parámetros válidos (con valor):', $validParameters);

        // Si no hay parámetros válidos, devuelve todo con filtro de fechas
        if (empty($validParameters)) {
            Log::info('No hay parámetros válidos, devolviendo todos los registros');
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            } elseif ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            }
            
            return $query->get()->toArray();
        }

        // Procesar solo el primer parámetro válido (el que tiene valor)
        foreach ($validParameters as $type => $value) {
            
            if (!array_key_exists($type, $this->reportMap)) {
                Log::info("Parámetro desconocido: {$type}");
                continue;
            }

            $columnName = $this->reportMap[$type]['column'];
            $dataType = $this->reportMap[$type]['type'];
            
            Log::info("Procesando parámetro: {$type}");
            Log::info("Columna: {$columnName}, Tipo: {$dataType}, Valor: {$value}");

            if ($dataType === 'numeric') {
                Log::info('Tipo de dato: numérico');

                // Extrae operador y valor
                preg_match('/^(>=|>|!=|<)(.*)/', $value, $matches);

                if (count($matches) > 0) {
                    $operator = $matches[1];
                    $cleanValue = (float) str_replace(',', '.', $matches[2]);
                    Log::info("Operador encontrado: {$operator}, Valor: {$cleanValue}");
                } else {
                    $operator = '>=';
                    $cleanValue = (float) str_replace(',', '.', $value);
                    Log::info("Sin operador, usando por defecto >=, Valor: {$cleanValue}");
                }

                $query->where($columnName, $operator, $cleanValue);

            } elseif ($dataType === 'text_observation') {
                Log::info('Tipo de dato: texto observación');
                $query->whereNotNull($columnName);

            } elseif ($dataType === 'text_others') {
                Log::info('Tipo de dato: texto otros');
                $query->whereNotNull($columnName);

            } elseif ($dataType === 'enum') {
                Log::info('Tipo de dato: enum');

                $cleanValue = $this->heatQualityMap[$value] ?? null;
                Log::info("Valor mapeado: {$cleanValue}");

                if ($cleanValue) {
                    $query->where($columnName, $cleanValue);
                } else {
                    Log::warning("Valor enum no válido: {$value}");
                }
            }

            // Solo procesa el primer parámetro válido
            break;
        }

        // Aplicar filtros de fecha
        if ($startDate && $endDate) {
            Log::info("Aplicando filtro de fechas: {$startDate} - {$endDate}");
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            Log::info("Aplicando filtro fecha inicio: {$startDate}");
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            Log::info("Aplicando filtro fecha fin: {$endDate}");
            $query->whereDate('date', '<=', $endDate);
        }

        $results = $query->get()->toArray();
        Log::info('Cantidad de resultados:', ['count' => count($results)]);

        return $results;
    }
}