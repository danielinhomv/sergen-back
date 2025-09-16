<?php

namespace App\Repositories\Management;

use App\Models\User;

class UserRepository
{
    public function find($id)
    {
        return User::find($id);
    }

    public function findByName($name)
    {
        return User::where('name', $name)->first();
    }
    
    // Code for BovinRepository would go here
}