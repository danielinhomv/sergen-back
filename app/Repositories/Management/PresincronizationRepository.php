<?php

namespace App\Repositories\Management;

use App\Models\Pre_sincronization;

class PresincronizationRepository
{

    public function create($request)
    {
        return Pre_sincronization::create($request->all());
    }
}
