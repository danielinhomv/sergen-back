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
        return $this->userRepository->getUser($name);
    }

    public function login($request)
    {
        $name = $request->input('name');
        $user = $this->getUser($name);

        if (isset($user['error'])) {
            return $user['error'];
        }

        if (!Hash::check($request->password, $user->password)) {
            return [
                'error' => 'The password is incorrect'
            ];
        }

        try {
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'message' => 'Logged in successfully!',
                'token' => $token
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to create token', 'details' => $e->getMessage()];
        }
    }

    public function updateUser($id, $request)
    {
        return $this->userRepository->updateUser($id, $request);
    }
}
