<?php

namespace App\Repositories\Management;

use App\Models\Bull;

class BullRepository
{
    public function exits($name,$user_id)
    {
        return Bull::where('name', $name)
            ->where('user_id', $user_id)
            ->exists();
    }

    public function create($request)
    {
        return Bull::create($request->all());
    }

    public function getById($id)
    {
        return Bull::find($id);
    }

    public function find($id, $user_id)
    {
        return Bull::where('id', $id)
            ->where('user_id', $user_id)
            ->first();
    }

    public function findById($id)
    {
        return Bull::find($id);
    }   
}
