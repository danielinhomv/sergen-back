<?php

namespace App\Services\Management;

use App\Repositories\Management\ConfirmatoryUltrasoundRepository;
use App\Repositories\Management\ControlBovineRepository;

use function PHPSTORM_META\map;

class ConfirmatoryUltrasoundService
{
    private ControlBovineRepository $controlBovineRepository;
    private ConfirmatoryUltrasoundRepository $confirmatoryUltrasoundRepository;

    public function __construct(
        ControlBovineRepository $controlBovineRepository,
        ConfirmatoryUltrasoundRepository $confirmatoryUltrasoundRepository
    ) {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->confirmatoryUltrasoundRepository = $confirmatoryUltrasoundRepository;
    }

    public function create($request)
    {

        try {
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $confirmatoryUltrasound = $this->confirmatoryUltrasoundRepository->create($request);

            if (!$confirmatoryUltrasound) {
                return ['error' => 'Failed to create Confirmatory Ultrasound'];
            }

            return [
                'message' => 'confirmatoryUltrasounds created successfully',
                'confirmatoryUltrasounds' => $this->toMapSingle($confirmatoryUltrasound)
            ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function toMapSingle($confirmatoryUltrasound)
    {

        return [
            'id' => $confirmatoryUltrasound->id,
            'status' => $confirmatoryUltrasound->status,
            'observation' => $confirmatoryUltrasound->observation,
            'refugo' => $confirmatoryUltrasound->refugo,
            'date' => $confirmatoryUltrasound->date
        ];
    }

    private function toMap($confirmatoryUltrasounds)
    {
        try {
            return $confirmatoryUltrasounds->map(function ($confirmatoryUltrasound) {
                return $this->toMapSingle($confirmatoryUltrasound);
            });
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function All($request)
    {
        try {
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $confirmatoryUltrasounds = $bovineControl->confirmatory_ultrasounds;

            if (!$confirmatoryUltrasounds || $confirmatoryUltrasounds->isEmpty()) {
                return [
                    'message' => 'No Confirmatory Ultrasound records found',
                    'confirmatoryUltrasounds' => []
                ];
            }

            return [
                'message' => 'confirmatoryUltrasounds retrievals successfully',
                'confirmatoryUltrasounds' => $this->toMap($confirmatoryUltrasounds)
            ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function update($request)
    {
        try {
            $confirmatoryUltrasound = $this->confirmatoryUltrasoundRepository->update($request);

            if (!$confirmatoryUltrasound) {
                return ['error' => 'Failed to update Confirmatory Ultrasound'];
            }

            return [
                'message' => 'Confirmatory Ultrasound updated successfully',
                'confirmatoryUltrasounds' => $this->toMapSingle($confirmatoryUltrasound)
            ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }
    
    public function delete($id)
    {
        try {
            $confirmatoryUltrasound = $this->confirmatoryUltrasoundRepository->delete($id);

            if (!$confirmatoryUltrasound) {
                return ['error' => 'Failed to delete Confirmatory Ultrasound'];
            }

            return [
                'message' => 'Confirmatory Ultrasound deleted successfully'
            ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }
}