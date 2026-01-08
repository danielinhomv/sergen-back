<?php

namespace App\Services\Management;

use App\Repositories\Management\BovineRepository;
use App\Repositories\Management\ControlBovineRepository;
use App\Repositories\Management\ControlRepository;

class ControlBovineService
{
    protected ControlBovineRepository $controlBovineRepository;
    protected BovineRepository $bovineRepository;
    protected ControlRepository $controlRepository;

    public function __construct(ControlBovineRepository $controlBovineRepository, BovineRepository $bovineRepository, ControlRepository $controlRepository)
    {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->bovineRepository = $bovineRepository;
        $this->controlRepository = $controlRepository;
    }

    public function createControlBovine($request)
    {
        try {
            $bovineId = $request->input('bovine_id');
            $controlId = $request->input('control_id');
            $propertyId = $request->input('property_id');

            $bovine  = $this->bovineRepository->findById($bovineId, $propertyId);
            if (!$bovine) {
                return ['error' => 'Bovine not found'];
            }

            $control = $this->controlRepository->findById($controlId, $propertyId);
            if (!$control) {
                return ['error' => 'Control not found'];
            }

            $existingControlBovine = $this->controlBovineRepository->findByBovineAndControl($bovineId, $controlId);
            if ($existingControlBovine) {
                return $existingControlBovine;
            }

            return $this->controlBovineRepository->create($bovineId, $controlId);

        } catch (\Exception $e) {
            return ['error' => 'An error occurred while creating Control Bovine: ' . $e->getMessage()];
        }
    }
}