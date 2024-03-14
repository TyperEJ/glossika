<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    const TOKEN_NAME = 'API_TOKEN';

    public function register(
        string $email,
        string $password
    ): string {
        $user = User::create([
            'email' => $email,
            'password' => $password,
        ]);

        return $this->login($user);
    }

    public function verifyCredentials(
        string $email,
        string $password
    ): bool {
        return Auth::attempt([
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function login(User $user): string
    {
        $token = $user->createToken(self::TOKEN_NAME);

        return $token->plainTextToken;
    }
}
