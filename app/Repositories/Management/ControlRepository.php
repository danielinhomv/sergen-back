<?php

namespace App\Repositories\Management;

use App\Models\Bovine;
use App\Models\Control;

class ControlRepository
{
    public function create($request)
    {
        return Control::create($request->all());
    }
    // Code for BovinRepository would go here
}
