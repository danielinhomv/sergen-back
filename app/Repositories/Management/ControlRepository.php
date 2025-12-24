<?php

namespace App\Repositories\Management;

use App\Models\Control;

class ControlRepository
{
    public function create($propertyId)
    {
        return Control::create([
            'property_id'=>$propertyId
        ]);
    }

    public function findById($id)
    {
        return Control::findOrFail($id);
    }

}
