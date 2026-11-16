<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeShare;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeShare>
 */
class RecipeShareFactory extends Factory
{
    protected $model = RecipeShare::class;

    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'sharer_id' => User::factory(),
            'share_token' => Str::random(32),
        ];
    }
}
