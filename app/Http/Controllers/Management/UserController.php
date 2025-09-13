<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateUser($id, Request $request)
    {
        $user = $this->userService->updateUser($id, $request);
        if (isset($user['error'])) {
            return response()->json(['error' => $user['error']], 400);
        }
        return response()->json($user);
    }

    
}
