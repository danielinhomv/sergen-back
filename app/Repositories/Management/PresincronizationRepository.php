<?php

namespace App\Repositories\Management;

use App\Models\Presincronization;

class PresincronizationRepository
{

    public function create($request)
    {
        return Presincronization::create($request->all());
    }

    public function update($request)
    {
        $presincronization = Presincronization::find($request->input('id'));
        if ($presincronization) {
            $presincronization->update($request->all());
            return $presincronization;
        }
        return null;
    }
}
