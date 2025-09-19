<?php

namespace App\Repositories\Management;

use App\Models\Current_session;
use App\Models\Property;

class PropertyRepository
{
    public function findById($id)
    {
        return Property::find($id);
    }

    public function findByUserId($user_id)
    {
        return Property::where('user_id', $user_id)->get();
    }

    public function exists ($name,$user_id)
    {
        return Property::where('name', $name)
            ->where('user_id', $user_id)
            ->exists();
    }

    public function create($request){
        return Property::create($request->all());
    }

    public function createCurrentSession($user_id,$property_id){
        return Current_session::create([
            'user_id'=>$user_id,
            'property_id'=>$property_id,
            'active'=> true
        ]);
    }
    // Code for BovinRepository would go here
}