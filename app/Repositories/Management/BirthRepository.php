<?php

namespace App\Repositories\Management;

use App\Models\Birth;

class BirthRepository
{
    public function create($request)
    {
        return Birth::create($request->all());
    }

    public function update($request)
    {
        $birth = Birth::find($request->input('id'));
        if ($birth) {
            $birth->update($request->all());
            return $birth;
        }
        return null;
    }
   
}