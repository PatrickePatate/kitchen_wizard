<?php

namespace App\Models;

use App\MealTypeEnum;
use App\Models\Miscs\RecipeLike;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use App\MealDifficultyEnum;

class Recipe extends Model
{
    use Searchable;

    protected $casts = [
        'pictures' => 'array',
        'ingredients' => 'array',
        'steps' => 'array',
        'utensils' => 'array',
        'times' => 'array',
        'meal_type' => MealTypeEnum::class,
        "difficulty" => MealDifficultyEnum::class,
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

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('recipe', ['recipe' => $this]),
        );
    }

    //todo:  Optimize to avoid loading tremendous amount of likes when/if a lot of user joins
    public function isLikedBy(User $user): bool
    {
        return $this->likes->firstWhere('user_id', $user->id) !== null;
    }

    public function toSearchableArray(): array
    {
        return $this->toArray();
    }
}
