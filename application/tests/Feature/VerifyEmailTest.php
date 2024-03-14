<?php

namespace Tests\Feature;

use App\Notifications\VerifyEmailByCode;
use App\Notifications\VerifyEmailByLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use EntityGenerator, RefreshDatabase;

    public function test_send_verify_email_by_link(): void
    {
        Notification::fake();

        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->postJson('/api/auth/send-verify-email-by-link');

        Notification::assertSentTo($user, VerifyEmailByLink::class);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Email verification send',
        ]);
    }

    public function test_send_verify_email_by_link_verified(): void
    {
        $user = $this->createUser();

        $user->markEmailAsVerified();

        $response = $this->actingAs($user)
            ->postJson('/api/auth/send-verify-email-by-link');

        $response->assertStatus(400);

        $response->assertJson([
            'message' => 'Email has been verified',
        ]);
    }

    public function test_send_verify_email_by_code(): void
    {
        Notification::fake();

        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->postJson('/api/auth/send-verify-email-by-code');

        Notification::assertSentTo($user, VerifyEmailByCode::class);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Email verification send',
        ]);
    }

    public function test_send_verify_email_by_code_verified(): void
    {
        $user = $this->createUser();

        $user->markEmailAsVerified();

        $response = $this->actingAs($user)
            ->postJson('/api/auth/send-verify-email-by-code');

        $response->assertStatus(400);

        $response->assertJson([
            'message' => 'Email has been verified',
        ]);
    }

    public function test_verify_email_by_link(): void
    {
        $user = $this->createUser([
            'verify_email_expired_at' => Carbon::now()->addMinutes(60),
        ]);

        $token = encrypt($user->getEmailForVerification());

        $response = $this->postJson('/api/auth/verify-email-by-link', [
            'token' => $token,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Verification success',
        ]);

        $user->refresh();

        $this->assertTrue($user->hasVerifiedEmail());
    }

    public function test_verify_email_by_code(): void
    {
        $user = $this->createUser([
            'verify_email_code' => Str::random(6),
            'verify_email_expired_at' => Carbon::now()->addMinutes(60),
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/auth/verify-email-by-code', [
                'code' => $user->getVerifyEmailCode(),
            ]);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Verification success',
        ]);

        $user->refresh();

        $this->assertTrue($user->hasVerifiedEmail());
    }
}
