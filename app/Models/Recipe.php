<?php

namespace App\Models;

use App\DietEnum;
use App\MealTypeEnum;
use App\Models\Miscs\RecipeLike;
use App\RecipeDurationEnum;
use App\Support\DurationParser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use App\MealDifficultyEnum;

class Recipe extends Model
{
    use HasFactory, Searchable;

    protected $casts = [
        'pictures' => 'array',
        'ingredients' => 'array',
        'steps' => 'array',
        'utensils' => 'array',
        'times' => 'array',
        'meal_type' => MealTypeEnum::class,
        "difficulty" => MealDifficultyEnum::class,
        "diet" => DietEnum::class,
    ];

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->timestamps = false;
            $model->scrapped_at = now();
        });

        static::updating(function ($model) {
            $model->timestamps = false;
        });
    }

    public function likes(): HasMany
    {
        return $this->hasMany(RecipeLike::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(RecipeShare::class);
    }

    public function share(): ?string
    {
        if ($user = auth()->user()) {
            $share = $this->shares()->create([
                'sharer_id' => $user->id,
                'share_token' => Str::random(32),
            ]);

            return route('recipe', ['recipe' => $this, 'share_token' => $share->share_token]);
        }

        return null;
    }

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('recipe', ['recipe' => $this]),
        );
    }

    public function durationMinutes(): Attribute
    {
        return Attribute::make(
            get: fn() => DurationParser::toMinutes($this->total_time),
        );
    }

    public function durationBucket(): Attribute
    {
        return Attribute::make(
            get: fn() => RecipeDurationEnum::fromMinutes($this->duration_minutes),
        );
    }

    //todo:  Optimize to avoid loading tremendous amount of likes when/if a lot of user joins
    public function isLikedBy(User $user): bool
    {
        return $this->likes->firstWhere('user_id', $user->id) !== null;
    }

    public function toSearchableArray(): array
    {
        return array_merge($this->toArray(), [
            'duration_minutes' => $this->duration_minutes,
            'duration_bucket' => $this->duration_bucket?->value,
        ]);
    }
}
