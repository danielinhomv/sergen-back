<?php

namespace App\Services\Management;

use App\Repositories\Management\PresincronizationRepository;

class PresincronizationService
{

    private PresincronizationRepository $presincronizationRepository;   

    public function __construct(PresincronizationRepository $presincronizationRepository)
    {
        $this->presincronizationRepository = $presincronizationRepository;
    }

    public function create($request)
    {
        return $this->presincronizationRepository->create($request);
    }

    public function get($request)
    {
        return $this->presincronizationRepository->get($request);
    }

    
}