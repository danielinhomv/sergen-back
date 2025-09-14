<?php

namespace App\Services\Management;

use App\Models\Control;
use App\Repositories\Management\ControlRepository;

class ControlService
{
    private ControlRepository $controlRepository;

    public function __construct(ControlRepository $controlRepository)
    {
        $this->controlRepository = $controlRepository;
    }
    
    public function startNewProtocol($property_id)
    {
        return $this->controlRepository->startNewProtocol($property_id);
    }
}
