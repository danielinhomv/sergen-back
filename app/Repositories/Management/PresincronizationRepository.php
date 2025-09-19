<?php

namespace App\Repositories\Management;

use App\Models\Presincronization;

class PresincronizationRepository
{

    public function create($request)
    {
        return Presincronization::create($request->all());
    }
}
