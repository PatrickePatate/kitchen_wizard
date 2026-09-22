<?php

namespace Tests\Unit\Services;

use App\MealDifficultyEnum;
use App\MealTypeEnum;
use App\Models\Recipe;
use App\Services\RecipeSelectorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeSelectorServiceTest extends TestCase
{
    use RefreshDatabase;

    private RecipeSelectorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RecipeSelectorService();
    }

    public function test_it_returns_a_recipe_of_the_requested_meal_type(): void
    {
        Recipe::factory()->mealType(MealTypeEnum::MAIN_COURSE)->create();
        Recipe::factory()->mealType(MealTypeEnum::STARTER)->create();

        $recipe = $this->service->getRecipe(MealTypeEnum::MAIN_COURSE);

        $this->assertNotNull($recipe);
        $this->assertSame(MealTypeEnum::MAIN_COURSE, $recipe->meal_type);
    }

    public function test_it_returns_null_when_no_recipe_matches_the_meal_type(): void
    {
        Recipe::factory()->mealType(MealTypeEnum::STARTER)->create();

        $recipe = $this->service->getRecipe(MealTypeEnum::DESSERT);

        $this->assertNull($recipe);
    }

    public function test_it_excludes_hard_recipes(): void
    {
        Recipe::factory()->mealType(MealTypeEnum::MAIN_COURSE)->difficulty(MealDifficultyEnum::HARD)->create();

        $recipe = $this->service->getRecipe(MealTypeEnum::MAIN_COURSE);

        $this->assertNull($recipe);
    }

    public function test_it_excludes_unpublished_recipes(): void
    {
        Recipe::factory()->mealType(MealTypeEnum::MAIN_COURSE)->unpublished()->create();

        $recipe = $this->service->getRecipe(MealTypeEnum::MAIN_COURSE);

        $this->assertNull($recipe);
    }

    public function test_it_excludes_recipes_from_the_avoid_list(): void
    {
        $keep = Recipe::factory()->mealType(MealTypeEnum::MAIN_COURSE)->create();
        $avoided = Recipe::factory()->mealType(MealTypeEnum::MAIN_COURSE)->create();

        $recipe = $this->service->getRecipe(MealTypeEnum::MAIN_COURSE, [$avoided->id]);

        $this->assertNotNull($recipe);
        $this->assertSame($keep->id, $recipe->id);
    }
}
