<?php

namespace App\Repositories\Management;

use App\Models\Bovine;
use App\Models\Control;

class ControlRepository
{
    public function create($propertyId)
    {
        return Control::create([
            'property_id'=>$propertyId
        ]);
    }

}
