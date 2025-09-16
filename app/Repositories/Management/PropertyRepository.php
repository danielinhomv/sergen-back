<?php

namespace App\Repositories\Management;

use App\Models\Bovine;
use App\Models\Property;

class PropertyRepository
{
    public function find($id)
    {
        return Bovine::find($id);
    }

    public function findByUserId($user_id)
    {
        return Bovine::where('user_id', $user_id)->get();
    }

    public function exists ($name,$user_id)
    {
        return Bovine::where('name', $name)
            ->where('user_id', $user_id)
            ->exists();
    }

    public function create($request){
        return Property::create($request->all());
    }
    // Code for BovinRepository would go here
}