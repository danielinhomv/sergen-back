<?php

namespace App\Repositories\Management;

use App\Models\Control_bovine;
use App\Models\Pre_sincronization;

class PresincronizationRepository
{

    public function create($request)
    {
        try {

            $presincronization = Pre_sincronization::create($request->all());
        
            if (!$presincronization) {
                return ['error' => 'Failed to create Presincronization'];
            }
            
            return $this->toMapSingle($presincronization);
        
        } catch (\Exception $e) {
            return ['error' => 'Failed to create Presincronization', 'details' => $e->getMessage()];
        }
    }

    private function toMapSingle($presincronization)
    {
        return [
            'id' => $presincronization->id,
            'reproductive_vaccine' => $presincronization->reproductive_vaccine,
            'sincrogest_product' => $presincronization->sincrogest_product,
            'antiparasitic_product' => $presincronization->antiparasitic_product,
            'vitamins_and_minerals' => $presincronization->vitamins_and_minerals,
            'application_date' => $presincronization->application_date,
        ];
    }

    public function get($request)
    {
        try {

            $bovineControl = Control_bovine::where('bovine-controls_id', $request->input('bovine-controls_id'))->get();
            
            if ($bovineControl->isEmpty()) {
                return ['error' => 'Bovine Control not found'];
            }

            $presincronizations = $bovineControl->pre_sincronization();
            
            if ($presincronizations->isEmpty()) {
                return ['error' => 'No Presincronizations found for this Bovine Control'];
            }

            return $this->toMapSingle($presincronizations);
        
        } catch (\Exception $e) {
            return ['error' => 'Failed to retrieve Presincronizations', 'details' => $e->getMessage()];
        }
    }
}
