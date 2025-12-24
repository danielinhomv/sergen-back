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
            $bovine  = $this->bovineRepository->findById($request->bovine_id);
            if (!$bovine) {
                return ['error' => 'Bovine not found'];
            }

            $control = $this->controlRepository->findById($request->control_id);
            if (!$control) {
                return ['error' => 'Control not found'];
            }

            return $this->controlBovineRepository->create($request);

        } catch (\Exception $e) {
            return ['error' => 'An error occurred while creating Control Bovine: ' . $e->getMessage()];
        }
    }
}