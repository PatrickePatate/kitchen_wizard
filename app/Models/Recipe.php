<?php

namespace App\Models;

use App\MealTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $casts = [
        'pictures' => 'array',
        'ingredients' => 'array',
        'steps' => 'array',
        'utensils' => 'array',
        'times' => 'array',
        'meal_type' => MealTypeEnum::class,
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

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('recipe', ['recipe' => $this]),
        );
    }
}
