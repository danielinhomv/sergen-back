<?php

namespace App\Repositories\Management;

use App\Models\Control_bovine;

class ControlBovineRepository
{

    public function find($id)
    {
        return Control_bovine::find($id);
    }

    public function create($request)
    {
        return Control_bovine::create($request->all());
    }
    // Code for ControlBovineRepository would go here
}