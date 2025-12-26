<?php

namespace App\Services\Management;

use App\Repositories\Management\ControlBovineRepository;
use App\Repositories\Management\GeneralPalpationRepository;

class GeneralPalpationService
{

    private ControlBovineRepository $controlBovineRepository;
    private GeneralPalpationRepository $generalPalpationRepository;

    public function __construct(
        ControlBovineRepository $controlBovineRepository,
        GeneralPalpationRepository $generalPalpationRepository
    ) {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->generalPalpationRepository = $generalPalpationRepository;
    }

    public function create($request)
    {
        try {

            $generalPalpation = $this->generalPalpationRepository->create($request);

            if (!$generalPalpation) {
                return ['error' => 'Failed to create Implant Retrieval'];
            }

            return
                [
                    'message' => 'General palpation created successfully',
                    'general palpation' => $this->toMapSingle($generalPalpation)
                ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function get($request)
    {
        try {
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $generalPalpation = $bovineControl->generalPalpation;

            if (!$generalPalpation) {
                return ['error' => 'Implant Retrieval not found'];
            }
            return
                [
                    'message' => 'General palpation retrieved successfully',
                    'general palpation' => $this->toMapSingle($generalPalpation)
                ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function update($request)
    {
        try {

            $generalPalpation = $this->generalPalpationRepository->update($request);

            if (!$generalPalpation) {
                return ['error' => 'Failed to update General Palpation'];
            }

            return
                [
                    'message' => 'General Palpation updated successfully',
                    'general palpation' => $this->toMapSingle($generalPalpation)
                ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function  toMapSingle($general_palpation)
    {
        try {
            return [
                'id' => $general_palpation->id,
                'status' => $general_palpation->status,
                'observation' => $general_palpation->observation,
                'date' => $general_palpation->date,
            ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }
}
