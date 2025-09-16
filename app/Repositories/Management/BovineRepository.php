<?php

namespace App\Repositories\Management;

use App\Models\Bovine;

class BovineRepository
{
    public function findBySerie($serie)
    {
        return Bovine::where('serie', $serie)->first();
    }

    public function create($request){
        return Bovine::create($request->all());
    }
    // Code for BovinRepository would go here
}
