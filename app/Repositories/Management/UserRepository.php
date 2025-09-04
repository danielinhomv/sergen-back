<?php

namespace App\Repositories\Management;

use App\Models\User;

class UserRepository
{
    public function getUser($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }
}
