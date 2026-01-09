<?php

namespace App\Repositories\Management;

use App\Models\Control_bovine;

class ControlBovineRepository
{

    public function find($id)
    {
        return Control_bovine::find($id);
    }

    public function create($bovine_id, $control_id)
    {
        return Control_bovine::create([
            'bovine_id' => $bovine_id,
            'control_id' => $control_id
        ]);
    }

    //buscar por bovine_id y control_id
    public function findByBovineAndControl($bovine_id, $control_id)
    {
        return Control_bovine::where('bovine_id', $bovine_id)
                              ->where('control_id', $control_id)
                              ->first();
    }

    public function lockForUpdate($id)
    {
        return Control_bovine::where('id', $id)
                             ->lockForUpdate()
                             ->first();
    }
}