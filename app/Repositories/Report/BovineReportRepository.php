<?php

namespace App\Repositories\Report;

use App\Models\Bovine;
use App\Models\Property;

class BovineReportRepository
{

    public function generateBovineHistoryReport($bovine_id, $property_id)
    {
        $property = Property::find($property_id);
        if (!$property) {
            return ['error' => 'Propiedad no encontrada.'];
        }
        
        $bovine = Bovine::find($bovine_id);
        if (!$bovine) {
            return ['error' => 'Bovino no encontrado.'];
        }

        $control = $property->control;
        if (!$control) {
            return ['error' => 'No hay un control asociado a esta propiedad.'];
        }

        $controlBovine = $control->control_bovines()
                         ->where('bovine_id', $bovine_id)
                         ->where('control_id', $control->id)
                         ->first();
        if (!$controlBovine) {
            return ['error' => 'El bovino no está asociado al control de esta propiedad.'];
        }
        
        $history = [];

        $history['inseminations'] = $controlBovine->inseminations;
        $history['ultrasound'] = $controlBovine->ultrasound;
        $history['pre_sincronization'] = $controlBovine->pre_sincronization;
        $history['implant_retrieval'] = $controlBovine->implant_retrieval;
        $history['general_palpation'] = $controlBovine->general_palpation;
        $history['confirmatory_ultrasounds'] = $controlBovine->confirmatory_ultrasounds;
        
        
        return $history;
    }

    public function generatePropertyBovineHistoryReport($property_id)
    {
        $property = Property::find($property_id);
        if (!$property) {
            return ['error' => 'Propiedad no encontrada.'];
        }

        $control = $property->control;
        if (!$control) {
            return ['error' => 'No hay un control asociado a esta propiedad.'];
        }

        $history = [];

        foreach ($control->control_bovines as $control_bovine) {
            $bovineHistory = [];
            $bovineHistory['bovine_id'] = $control_bovine->bovine_id;
            
            // CORRECCIÓN: Removido ->get()
            $bovineHistory['inseminations'] = $control_bovine->inseminations;
            $bovineHistory['ultrasound'] = $control_bovine->ultrasound;
            $bovineHistory['pre_sincronization'] = $control_bovine->pre_sincronization;
            $bovineHistory['implant_retrieval'] = $control_bovine->implant_retrieval;
            $bovineHistory['general_palpation'] = $control_bovine->general_palpation;
            $bovineHistory['confirmatory_ultrasounds'] = $control_bovine->confirmatory_ultrasounds;

            $history[] = $bovineHistory;
        }

        return $history;
    }

}