<?php

namespace App\Repositories\Management;

use App\Models\General_palpation;

class GeneralPalpationRepository
{

    public function create($request)
    {
        return General_palpation::create($request->all());
    }

    public function update($request)
    {
        $generalPalpation = General_palpation::find($request->input('id'));
        if ($generalPalpation) {
            $generalPalpation->update($request->all());
            return $generalPalpation;
        }
        return null;
    }

}