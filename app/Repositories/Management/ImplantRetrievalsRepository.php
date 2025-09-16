<?php

namespace App\Repositories\Management;

use App\Models\Implant_retrieval;

class ImplantRetrievalsRepository
{

    public function create($request)
    {
        return Implant_retrieval::create($request->all());
    }

}