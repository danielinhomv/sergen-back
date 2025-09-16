<?php

namespace App\Repositories\Management;

use App\Models\Confirmatory_ultrasound;

class ConfirmatoryUltrasoundRepository
{


    public function create($request)
    {
        return Confirmatory_ultrasound::create($request->all());
    }

    // Code for ControlBovineRepository would go here
}