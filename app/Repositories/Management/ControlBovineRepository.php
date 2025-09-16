<?php

namespace App\Repositories\Management;

use App\Models\Control_bovine;

class ControlBovineRepository
{

    public function find($id)
    {
        return Control_bovine::find($id);
    }
    // Code for ControlBovineRepository would go here
}