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

    //buscar por bovine_id y control_id
    public function findByBovineAndControl($bovine_id, $control_id)
    {
        return Control_bovine::where('bovine_id', $bovine_id)
                              ->where('control_id', $control_id)
                              ->first();
    }
}