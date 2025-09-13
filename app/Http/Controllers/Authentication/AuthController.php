<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Management\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(Request $request)
    {
        $loginResponse = $this->userService->login($request);

        if (isset($loginResponse['error'])) {
            return response()->json(['error' => $loginResponse['error']], 400);
        }

        return response()->json($loginResponse);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully!']);
    }
}
