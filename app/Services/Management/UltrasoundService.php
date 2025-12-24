<?php

namespace App\Services\Management;


use App\Repositories\Management\ControlBovineRepository;
use App\Repositories\Management\UltrasoundRepository;

class UltrasoundService
{
    private ControlBovineRepository $controlBovineRepository;
    private UltrasoundRepository $ultrasoundRepository;

    public function __construct(ControlBovineRepository $controlBovineRepository, UltrasoundRepository $ultrasoundRepository)
    {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->ultrasoundRepository = $ultrasoundRepository;
    }

    public function create($request)
    {

        try {
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $ultrasound = $this->ultrasoundRepository->create($request);

            if (!$ultrasound) {
                return ['error' => 'Failed to create Implant Retrieval'];
            }

            return
                [
                    'message' => 'Ultrasound created successfully',
                    'ultrasound' => $this->toMapSingle($ultrasound)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function toMapSingle($ultrasound)
    {
        try {
            return [
                'id' => $ultrasound->id,
                'vitamins_and_minerals' => $ultrasound->vitamins_and_minerals,
                'status' => $ultrasound->status,
                'protocol_details' => $ultrasound->protocol_details,
                'used_products_summary' => $ultrasound->used_products_summary,
                'work_team' => $ultrasound->work_team,
                'date' => $ultrasound->date
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

            $ultrasound = $bovineControl->ultrasound;

            if (!$ultrasound) {
                return ['error' => 'Implant Retrieval not found'];
            }

            return
                [
                    'message' => 'ultrasound retrieved successfully',
                    'ultrasound' => $this->toMapSingle($ultrasound)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function update($request)
    {
        try {
            $ultrasound = $this->ultrasoundRepository->update($request);

            if (!$ultrasound) {
                return ['error' => 'Failed to update Ultrasound'];
            }

            return
                [
                    'message' => 'ultrasound updated successfully',
                    'ultrasound' => $this->toMapSingle($ultrasound)
                ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }
}
