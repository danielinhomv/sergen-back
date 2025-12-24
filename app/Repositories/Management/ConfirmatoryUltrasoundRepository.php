<?php

namespace App\Repositories\Management;

use App\Models\Confirmatory_ultrasound;

class ConfirmatoryUltrasoundRepository
{


    public function create($request)
    {
        return Confirmatory_ultrasound::create($request->all());
    }

    public function update($request)
    {
        $confirmatoryUltrasound = Confirmatory_ultrasound::find($request->input('id'));
        if ($confirmatoryUltrasound) {
            $confirmatoryUltrasound->update($request->all());
            return $confirmatoryUltrasound;
        }
        return null;
    }

    // Code for ControlBovineRepository would go here
}