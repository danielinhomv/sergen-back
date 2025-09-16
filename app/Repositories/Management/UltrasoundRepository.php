<?php

namespace App\Repositories\Management;

use App\Models\Ultrasound;

class UltrasoundRepository 
{

    public function create($request){
        return Ultrasound::create($request->all());
    }
    
    // Code for BovinRepository would go here
}