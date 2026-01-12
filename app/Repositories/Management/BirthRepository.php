<?php

namespace App\Repositories\Management;

use App\Models\Birth;
use App\Models\Insemination;

class BirthRepository
{
    public function createRaw($data)
    {
        return Birth::create($data);
    }

    public function update($request)
    {
        $birth = Birth::find($request->input('id'));
        if ($birth) {
            $birth->type_of_birth = $request->input('type_of_birth', $birth->type_of_birth);
            $birth->control_bovine_id = $request->input('control_bovine_id', $birth->control_bovine_id);
            $birth->save();
            return $birth;
        }
        return null;
    }

    public function getBullNameByControlBovineId($controlBovineId)
    {
        $lastInsemination = Insemination::where('control_bovine_id', $controlBovineId)
            ->orderBy('date', 'desc')
            ->first();

        return $lastInsemination?->bull?->name;
    }
}
