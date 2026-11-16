<?php

namespace Database\Factories\Miscs;

use App\Models\Miscs\RecipeLike;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Miscs\RecipeLike>
 */
class RecipeLikeFactory extends Factory
{
    protected $model = RecipeLike::class;

    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'user_id' => User::factory(),
            'liked_at' => now(),
        ];
    }
}
