<?php

namespace App\Repositories\Management;

use App\Models\User;

class UserRepository
{
    public function getUser($name)
    {
        $user = User::where('name', $name)->first();
        return $user;
    }
}
