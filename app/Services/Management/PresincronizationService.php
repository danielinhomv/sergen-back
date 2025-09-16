<?php

namespace App\Services\Management;

use App\Repositories\Management\ControlBovineRepository;
use App\Repositories\Management\PresincronizationRepository;

class PresincronizationService
{

    private ControlBovineRepository $controlBovineRepository;
    private PresincronizationRepository $presincronizationRepository;

    public function __construct(ControlBovineRepository $controlBovineRepository,PresincronizationRepository $presincronizationRepository)
    {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->presincronizationRepository = $presincronizationRepository;
    }

    public function create($request)
    {
        try {

            $presincronization = $this->presincronizationRepository->create($request);

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
        try {
            return [
                'id' => $presincronization->id,
                'reproductive_vaccine' => $presincronization->reproductive_vaccine,
                'sincrogest_product' => $presincronization->sincrogest_product,
                'antiparasitic_product' => $presincronization->antiparasitic_product,
                'vitamins_and_minerals' => $presincronization->vitamins_and_minerals,
                'application_date' => $presincronization->application_date,
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to create Presincronization', 'details' => $e->getMessage()];
        }
    }

    public function get($request)
    {
        try {

            $bovineControl = $this->controlBovineRepository->find($request->input('bovine-controls_id'));
            
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
