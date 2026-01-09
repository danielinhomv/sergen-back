<?php

namespace App\Repositories\Management;

use App\Models\Control;

class ControlRepository
{
    public function create($propertyId, $startDate, $endDate)
    {
        return Control::create([
            'property_id'=>$propertyId,
            'start_date'=>$startDate,
            'end_date'=>$endDate ?? null
        ]);
    }

    public function findById($id)
    {
        return Control::find($id);
    }

    //todos los controles de una propiedad ordenados por fecha descendente
    public function findByPropertyId($propertyId)
    {
        return Control::where('property_id', $propertyId)
            ->orderBy('start_date', 'desc')
            ->get();
    }
}
