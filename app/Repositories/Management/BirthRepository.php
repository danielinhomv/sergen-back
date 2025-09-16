<?php

namespace App\Repositories\Management;

use App\Models\Birth;

class BirthRepository
{
    public function create($request)
    {
        return Birth::create($request->all());
    }
   
}