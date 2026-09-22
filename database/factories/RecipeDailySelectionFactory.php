<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\RecipeDailySelection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeDailySelection>
 */
class RecipeDailySelectionFactory extends Factory
{
    protected $model = RecipeDailySelection::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipes_selection' => [
                'starter' => Recipe::factory()->mealType(\App\MealTypeEnum::STARTER)->create()->id,
                'main' => Recipe::factory()->mealType(\App\MealTypeEnum::MAIN_COURSE)->create()->id,
                'dessert' => Recipe::factory()->mealType(\App\MealTypeEnum::DESSERT)->create()->id,
            ],
        ];
    }
}
