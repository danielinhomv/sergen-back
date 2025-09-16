<?php

namespace App\Repositories\Management;

use App\Models\Bovine;
use App\Models\Control;

class ControlRepository
{
    public function create($property_id)
    {
        return Control::create([
            'status' => 'in progress',
            'property_id' => $property_id,
        ]);
    }
    // Code for BovinRepository would go here
}
