<?php

namespace App\Repositories\Management;

use App\Models\General_palpation;

class GeneralPalpationRepository
{

    public function create($request)
    {
        return General_palpation::create($request->all());
    }

}