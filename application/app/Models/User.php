<?php

namespace App\Models;

use App\Notifications\VerifyEmailByCode;
use App\Notifications\VerifyEmailByLink;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'verify_email_code',
        'verify_email_expired_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verify_email_expired_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function findOrFailByEmail(string $email): self
    {
        return self::query()
            ->where('email', '=', $email)
            ->firstOrFail();
    }

    public function findByEmail(string $email): ?self
    {
        return self::query()
            ->where('email', '=', $email)
            ->first();
    }

    public static function create(array $attributes): self
    {
        $user = new self();

        $user->fill($attributes);

        $user->save();

        return $user;
    }

    public function sendEmailVerificationByLink(): void
    {
        $this->markVerifyEmailExpiredTime();

        $this->notify(new VerifyEmailByLink);
    }

    public function sendEmailVerificationByCode(): void
    {
        $this->markVerifyEmailExpiredTime();

        $this->generateEmailVerifyCode();

        $this->notify(new VerifyEmailByCode);
    }

    public function getVerifyEmailCode(): string
    {
        return $this->getAttributeValue('verify_email_code');
    }

    private function generateEmailVerifyCode(): void
    {
        $this->forceFill([
            'verify_email_code' => Str::random(6),
        ])->save();
    }

    private function markVerifyEmailExpiredTime(): void
    {
        $this->forceFill([
            'verify_email_expired_at' => Carbon::now()->addMinutes(60),
        ])->save();
    }

    public function getVerifyEmailExpiredAt(): ?Carbon
    {
        return $this->getAttributeValue('verify_email_expired_at');
    }
}
