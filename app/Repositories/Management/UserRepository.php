<?php

namespace App\Repositories\Management;

use App\Models\User;

class UserRepository
{
    public function getUser($name)
    {
        try {
            $user = User::where('name', $name)->first();
            if (!$user) {
                return ['error' => 'User not found'];
            }
            return $user;
        } catch (\Exception $e) {
            return ['error' => 'Failed to retrieve user', 'details' => $e->getMessage()];
        }

    }

    public function updateUser($id, $request)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return ['error' => 'User not found'];
            }
            $user->update($request);
            return [
                'message' => 'User updated successfully',
                'user' => $user
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to update user', 'details' => $e->getMessage()];
        }
    }
}
