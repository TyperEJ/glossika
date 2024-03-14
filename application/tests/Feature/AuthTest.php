<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use EntityGenerator, RefreshDatabase;

    public function test_register_least_char(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'test@test.com',
            'password' => 'A8888',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The password field must be at least 6 characters. (and 1 more error)',
        ]);
    }

    public function test_register_greater_char(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'test@test.com',
            'password' => 'Ab8888888888888888888',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The password field must not be greater than 16 characters. (and 1 more error)',
        ]);
    }

    public function test_register_special_symbol(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'test@test.com',
            'password' => '88888888',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The password field format is invalid.',
        ]);
    }

    public function test_register_email(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'test',
            'password' => 'Ab@888',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The email field must be a valid email address.',
        ]);
    }

    public function test_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'test@test.com',
            'password' => 'Ab@888',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Registration success',
        ]);
    }

    public function test_login_not_found(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@test.com',
            'password' => 'Ab@888',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Unauthenticated',
        ]);
    }

    public function test_login(): void
    {
        $credentials = [
            'email' => 'test@test.com',
            'password' => 'Ab@888',
        ];

        $this->createUser($credentials);

        $response = $this->postJson('/api/auth/login', $credentials);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Login success',
        ]);
    }
}
