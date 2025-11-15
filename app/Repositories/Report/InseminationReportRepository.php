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
        'observation' => ['column' => 'observation', 'type' => 'text'],
        'others' => ['column' => 'others', 'type' => 'text'],
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

        // Si no hay parámetros, devuelve todo
        if (empty($parameters)) {
            return $query->get()->toArray();
        }
        Log::info('query---------------');

        Log::info('entrando al foreach...............');
        // Procesa cada parámetro
        foreach ($parameters as $type => $values) {
            // Normaliza: si viene como string, conviértelo a array
            if (!is_array($values)) {
                $values = [$values];
            }

            if (!array_key_exists($type, $this->reportMap)) {
                continue; // ignora parámetros desconocidos
            }

            $columnName = $this->reportMap[$type]['column'];
            $dataType = $this->reportMap[$type]['type'];
            Log::info($columnName);
            Log::info($dataType);


            foreach ($values as $value) {
                if ($dataType === 'numeric') {
                    // Extrae operador y valor
                    Log::info('el tipo de dato es numerico');

                    preg_match('/^(>=|>|!=|<)(.*)/', $value, $matches);
                    
                    if (count($matches) > 0) {
                        Log::info('es mayor que cero la cantidad de operadores');
                        
                        $operator = $matches[1];
                        
                        Log::info($operator);
                        
                        $cleanValue = (float) str_replace(',', '.', $matches[2]);
                        
                        Log::info($cleanValue);
                    
                    } else {
                        $operator = '>=';
                        $cleanValue = (float) str_replace(',', '.', $value);
                    }
                    
                    $query->where($columnName, $operator, $cleanValue);
                    
                    Log::info($query->get()->toArray());
                
                } elseif ($dataType === 'text') {
                
                    Log::info('es texto');
                    $query->whereNotNull($columnName);
                
                } elseif ($dataType === 'enum') {
                
                    Log::info('es enum');
                
                    $cleanValue = $this->heatQualityMap[$value] ?? null;
                
                    if ($cleanValue) {
                        $query->where($columnName, $cleanValue);
                    }
                }
            }
            break; // Solo procesa el primer parámetro que tiene asignado un valor 
        }
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        return $query->get()->toArray();
    }
}
