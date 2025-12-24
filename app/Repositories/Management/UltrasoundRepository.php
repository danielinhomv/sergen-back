<?php

namespace App\Repositories\Management;

use App\Models\Ultrasound;

class UltrasoundRepository 
{

    public function create($request){
        return Ultrasound::create($request->all());
    }

    public function update($request)
    {
        $ultrasound = Ultrasound::find($request->input('id'));
        if ($ultrasound) {
            $ultrasound->update($request->all());
            return $ultrasound;
        }
        return null;
    }
    
    // Code for BovinRepository would go here
}