<?php

namespace App\Services\Management;

use App\Repositories\Management\BovineRepository;

class BovineService
{
    private BovineRepository $bovineRepository;

    public function __construct(BovineRepository $bovineRepository)
    {
        $this->bovineRepository = $bovineRepository;
    }

    public function create($request)
    {
        return $this->bovineRepository->create($request);
    }

    public function all($property_id)
    {
        return $this->bovineRepository->all($property_id);
    }

}