<?php

namespace App\Repositories\Management;

use App\Models\Control;

class ControlRepository
{
    public function create($propertyId)
    {
        return Control::create([
            'property_id'=>$propertyId,
            'status'=>'in progress',
            'start_date'=>now()
        ]);
    }

    public function findById($id)
    {
        return Control::findOrFail($id);
    }

    public function getLastControl($propertyId)
    {
        return Control::where('property_id', $propertyId)   
            ->orderBy('start_date', 'desc')
            ->first();
    }
}
