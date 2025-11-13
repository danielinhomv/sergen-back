<?php

use App\Models\Bovine;
use App\Models\Property;

class BovineReportRepository
{

    public function generateBovineHistoryReport($bovine_id,$property_id)
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

        $control_bovine = $control->control_bovines()
                         ->where('bovine_id', $bovine_id)
                         ->where('control_id', $control->id)
                         ->first();
        if (!$control_bovine) {
            return ['error' => 'El bovino no está asociado al control de esta propiedad.'];
        }
        
        $history = [];

        $history['inseminations'] = $control_bovine->inseminations->get();
        $history['ultrasound'] = $control_bovine->ultrasound->get();
        $history['pre_sincronization'] = $control_bovine->pre_sincronization->get();
        $history['implant_retrieval'] = $control_bovine->implant_retrieval->get();
        $history['general_palpation'] = $control_bovine->general_palpation->get();
        $history['confirmatory_ultrasounds'] = $control_bovine->confirmatory_ultrasounds->get();
        
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
            $bovineHistory['inseminations'] = $control_bovine->inseminations->get();
            $bovineHistory['ultrasound'] = $control_bovine->ultrasound->get();
            $bovineHistory['pre_sincronization'] = $control_bovine->pre_sincronization->get();
            $bovineHistory['implant_retrieval'] = $control_bovine->implant_retrieval->get();
            $bovineHistory['general_palpation'] = $control_bovine->general_palpation->get();
            $bovineHistory['confirmatory_ultrasounds'] = $control_bovine->confirmatory_ultrasounds->get();

            $history[] = $bovineHistory;
        }

        return $history;
    }

}