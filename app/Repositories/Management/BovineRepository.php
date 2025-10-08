<?php

namespace App\Repositories\Management;

use App\Models\Bovine;

class BovineRepository
{

    public function create($request)
    {
        return Bovine::create($request->all());
    }

    public function existSerie($serie, $propertyId)
    {
        return Bovine::where('property_id', $propertyId)
            ->where('serie', $serie)
            ->get();
    }

    public function existRgd($rgd, $propertyId)
    {
        return Bovine::where('property_id', $propertyId)
            ->where('rgd', $rgd)
            ->get();
    }
}
