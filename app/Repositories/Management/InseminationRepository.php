<?php

namespace App\Repositories\Management;

use App\Models\Insemination;

class InseminationRepository
{
    public function create($request)
    {
        return Insemination::create($request->all());
    }
    
    public function findById($id){
        //se buscara la inseminacion por su id
    }

}
