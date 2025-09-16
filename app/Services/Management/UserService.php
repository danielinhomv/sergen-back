<?php

namespace App\Services\Management;

use App\Repositories\Management\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function getUser($name)
    {
        try {
            $user = $this->userRepository->findByName($name);

            if (!$user) {
                return ['error' => 'User not found'];
            }
            return $user;
        } catch (\Exception $e) {
            return ['error' => 'Failed to retrieve user', 'details' => $e->getMessage()];
        }

    }

    public function updateUser($request)
    {
        try {
            $user = $this->userRepository->find($request->input('user_id'));

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

    public function login ($request)
    {
        try {
            $name = $request->input('name');
            $password = $request->input('password');

            $user = $this->userRepository->findByName($name);

            if (!$user || !Hash::check($password, $user->password)) {
                return ['error' => 'Invalid credentials'];
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'message' => 'Login successful',
                'token' => $token,
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to login', 'details' => $e->getMessage()];
        }
    }
}
