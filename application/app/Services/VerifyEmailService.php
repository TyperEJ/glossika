<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class VerifyEmailService
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function verifyByLink(string $token): bool
    {
        try {
            $email = Crypt::decrypt($token);
        } catch (DecryptException $decryptException) {
            return false;
        }

        $user = $this->user->findByEmail($email);

        if (is_null($user)) {
            return false;
        }

        if (! $user->getVerifyEmailExpiredAt()?->isAfter(Carbon::now())) {
            return false;
        }

        return $user->markEmailAsVerified();
    }

    public function verifyByCode(User $user, string $code): bool
    {
        if (
            $user->getVerifyEmailCode() !== $code ||
            ! $user->getVerifyEmailExpiredAt()?->isAfter(Carbon::now())
        ) {
            return false;
        }

        return $user->markEmailAsVerified();
    }
}
