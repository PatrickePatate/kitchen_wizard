<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Miscs\RecipeLike;
use App\UserGroupEnum;
use Cache;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'group' => UserGroupEnum::class
        ];
    }

    public function likedRecipes(): HasMany
    {
        return $this->hasMany(RecipeLike::class)->orderBy('liked_at', 'desc');
    }

    public function dailySelections(): HasMany
    {
        return $this->hasMany(RecipeDailySelection::class)->orderBy('created_at', 'desc');
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
        return !($this->isEmailNotificationsActive() || $this->isTelegramAccountSetup());
    }

    public function routeNotificationForDiscord()
    {
        return $this->discord_private_channel_id ?? $this->discord_user_id;
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function(User $user) {
            $user->dailySelections()->delete();
            $user->likedRecipes()->delete();
      });
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
