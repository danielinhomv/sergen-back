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
            $bovineControl = $this->controlBovineRepository->find($request->input('bovine-controls_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $confirmatoryUltrasound = $this->confirmatoryUltrasoundRepository->create($request);

            if (!$confirmatoryUltrasound) {
                return ['error' => 'Failed to create Implant Retrieval'];
            }

            return [
                'message' => 'Confirmatory_ultrasound created successfully',
                'confirmatory_ultrasound' => $this->toMapSingle($confirmatoryUltrasound)
            ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function toMapSingle($ultrasound)
    {

        return [
            'id' => $ultrasound->id,
            'status' => $ultrasound->status,
            'observation' => $ultrasound->observation,
            'date' => $ultrasound->date
        ];
    }

    private function toMap($confirmatoryUltrasounds)
    {
        try {
            return $confirmatoryUltrasounds . map(function ($confirmatoryUltrasound) {
                return $this->toMapSingle($confirmatoryUltrasound);
            });
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function All($request)
    {
        try {
            $bovineControl = $this->controlBovineRepository->create($request);

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $ultrasounds = $bovineControl->confirmatoryUltrasounds();

            if (!$ultrasounds) {
                return ['error' => 'Implant Retrieval not found'];
            }
            
            return [
                'message' => 'Confirmatory_ultrasound retrievals successfully',
                'confirmatory_ultrasound' => $this->toMap($ultrasounds)
            ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }
}
