<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->authService->register(
            $request->input('email'),
            $request->input('password')
        );

        return response()->json([
            'message' => 'Registration success',
            'token' => $token,
        ]);
    }

    public function login(LoginRequest $request, User $user): JsonResponse
    {
        if (! $this->authService->verifyCredentials(
            $request->input('email'),
            $request->input('password')
        )) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user = $user->findOrFailByEmail($request->input('email'));

        $token = $this->authService->login($user);

        return response()->json([
            'message' => 'Login success',
            'token' => $token,
        ]);
    }
}
