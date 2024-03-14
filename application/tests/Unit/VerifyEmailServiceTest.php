<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\VerifyEmailService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\Framework\TestCase;

class VerifyEmailServiceTest extends TestCase
{
    public function test_verify_by_link_not_found(): void
    {
        $user = \Mockery::mock(User::class);
        $user->shouldReceive('findByEmail')
            ->andReturnNull();

        $token = 'token';

        Crypt::clearResolvedInstances();

        Crypt::shouldReceive('decrypt')
            ->with($token)
            ->andReturn('test@test.com');

        $service = new VerifyEmailService($user);

        $isVerify = $service->verifyByLink($token);

        $this->assertFalse($isVerify);
    }

    public function test_verify_by_link_time_out(): void
    {
        $user = \Mockery::mock(User::class);
        $user->shouldReceive('findByEmail')
            ->andReturnSelf();

        $user->shouldReceive('getVerifyEmailExpiredAt->isAfter')
            ->andReturnFalse();

        $token = 'token';

        Crypt::clearResolvedInstances();

        Crypt::shouldReceive('decrypt')
            ->with($token)
            ->andReturn('test@test.com');

        $service = new VerifyEmailService($user);

        $isVerify = $service->verifyByLink($token);

        $this->assertFalse($isVerify);
    }

    public function test_verify_by_link_decrypt_fail(): void
    {
        $user = \Mockery::mock(User::class);

        $token = 'token';

        Crypt::clearResolvedInstances();

        Crypt::shouldReceive('decrypt')
            ->with($token)
            ->andThrow(new DecryptException);

        $service = new VerifyEmailService($user);

        $isVerify = $service->verifyByLink($token);

        $this->assertFalse($isVerify);
    }

    public function test_verify_by_link(): void
    {
        $user = \Mockery::mock(User::class);
        $user->shouldReceive('findByEmail')
            ->andReturnSelf();

        $user->shouldReceive('getVerifyEmailExpiredAt->isAfter')
            ->andReturnTrue();

        $user->shouldReceive('markEmailAsVerified')
            ->andReturnTrue();

        $token = 'token';

        Crypt::clearResolvedInstances();

        Crypt::shouldReceive('decrypt')
            ->with($token)
            ->andReturn('test@test.com');

        $service = new VerifyEmailService($user);

        $isVerify = $service->verifyByLink($token);

        $this->assertTrue($isVerify);
    }

    public function test_verify_by_code_error_code(): void
    {
        $code = 'code';

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('getVerifyEmailCode')
            ->andReturn('fake_code');

        $service = new VerifyEmailService($user);

        $isVerify = $service->verifyByCode($user, $code);

        $this->assertFalse($isVerify);
    }

    public function test_verify_by_code_time_out(): void
    {
        $code = 'code';

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('getVerifyEmailCode')
            ->andReturn($code);

        $user->shouldReceive('getVerifyEmailExpiredAt->isAfter')
            ->andReturnFalse();

        $service = new VerifyEmailService($user);

        $isVerify = $service->verifyByCode($user, $code);

        $this->assertFalse($isVerify);
    }

    public function test_verify_by_code(): void
    {
        $code = 'code';

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('getVerifyEmailCode')
            ->andReturn($code);

        $user->shouldReceive('getVerifyEmailExpiredAt->isAfter')
            ->andReturnTrue();

        $user->shouldReceive('markEmailAsVerified')
            ->andReturnTrue();

        $service = new VerifyEmailService($user);

        $isVerify = $service->verifyByCode($user, $code);

        $this->assertTrue($isVerify);
    }
}
