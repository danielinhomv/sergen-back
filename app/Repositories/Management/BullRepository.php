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

    public function create($name , $user_id)
    {
        return Bull::create([
                'name' => $name,
                'user_id' => $user_id
            ]);
    }
}
