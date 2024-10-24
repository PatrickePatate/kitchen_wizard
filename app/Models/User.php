<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Cache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isTelegramAccountSetup(): bool
    {
        return !empty($this->telegram_chat_id && $this->telegram_validated);
    }

    public function isEmailNotificationsActive(): bool
    {
        return (bool) $this->is_email_notifications_active;
    }

    public function hasAtLeastOneNotificationChannelActive(): bool
    {
        return ($this->isEmailNotificationsActive() || $this->isTelegramAccountSetup());
    }

    public function initials(): Attribute
    {
        return Attribute::make(
            get: fn() => collect(explode(' ', $this->name))
                ->map(fn(string $name) => strtoupper($name[0]))
                ->join(''),
        );
    }

    public function avatar(): Attribute
    {
        return Attribute::make(
            get: fn() => 'https://www.gravatar.com/avatar/'.hash('sha256',strtolower(trim($this->email))).'?s=250&d=identicon',
        );
    }
}
