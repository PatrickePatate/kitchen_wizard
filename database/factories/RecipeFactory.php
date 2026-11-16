<?php

namespace Database\Factories;

use App\DietEnum;
use App\MealDifficultyEnum;
use App\MealTypeEnum;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'url' => fake()->unique()->url(),
            'pictures' => [fake()->imageUrl()],
            'total_time' => '30 min',
            'times' => ['prep' => '10 min', 'cook' => '20 min', 'rest_time' => '0 min'],
            'difficulty' => fake()->randomElement([
                MealDifficultyEnum::VERY_EASY,
                MealDifficultyEnum::EASY,
                MealDifficultyEnum::MEDIUM,
            ])->value,
            'price' => fake()->randomElement(['bon marché', 'moyen', 'cher']),
            'meal_type' => fake()->randomElement(MealTypeEnum::cases())->value,
            'ingredients' => [
                ['label' => fake()->word(), 'quantity' => 100, 'quantity_text' => '100 g'],
                ['label' => fake()->word(), 'quantity' => 2, 'quantity_text' => '2'],
            ],
            'utensils' => [['label' => fake()->word()]],
            'steps' => [
                ['heading' => 'Étape 1', 'text' => fake()->sentence()],
                ['heading' => 'Étape 2', 'text' => fake()->sentence()],
            ],
            'author' => fake()->name(),
            'author_note' => fake()->sentence(),
            'people' => fake()->numberBetween(1, 8),
            'published' => true,
            'seasonality' => fake()->randomElement(['spring', 'summer', 'autumn', 'winter', 'unknown']),
            'diet' => fake()->randomElement(DietEnum::cases())->value,
        ];
    }

    public function mealType(MealTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'meal_type' => $type->value,
        ]);
    }

    public function difficulty(MealDifficultyEnum $difficulty): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty' => $difficulty->value,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'published' => false,
        ]);
    }
}
