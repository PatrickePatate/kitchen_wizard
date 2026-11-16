<?php

namespace Tests\Unit\Models;

use App\MealTypeEnum;
use App\Models\Recipe;
use App\Models\RecipeDailySelection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeDailySelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_for_user_returns_null_when_there_is_no_selection_today(): void
    {
        $user = User::factory()->create();

        $this->assertNull(RecipeDailySelection::forUser($user));
    }

    public function test_for_user_returns_todays_selection(): void
    {
        $user = User::factory()->create();
        $selection = RecipeDailySelection::factory()->create(['user_id' => $user->id]);

        $found = RecipeDailySelection::forUser($user);

        $this->assertNotNull($found);
        $this->assertSame($selection->id, $found->id);
    }

    public function test_for_user_returns_the_most_recent_selection_when_several_exist_today(): void
    {
        $user = User::factory()->create();
        RecipeDailySelection::factory()->create(['user_id' => $user->id, 'created_at' => now()->subHours(2)]);
        $latest = RecipeDailySelection::factory()->create(['user_id' => $user->id, 'created_at' => now()]);

        $found = RecipeDailySelection::forUser($user);

        $this->assertSame($latest->id, $found->id);
    }

    public function test_starter_main_and_dessert_resolve_the_selected_recipes(): void
    {
        $starter = Recipe::factory()->mealType(MealTypeEnum::STARTER)->create();
        $main = Recipe::factory()->mealType(MealTypeEnum::MAIN_COURSE)->create();
        $dessert = Recipe::factory()->mealType(MealTypeEnum::DESSERT)->create();

        $selection = RecipeDailySelection::factory()->create([
            'recipes_selection' => [
                'starter' => $starter->id,
                'main' => $main->id,
                'dessert' => $dessert->id,
            ],
        ]);

        $this->assertSame($starter->id, $selection->starter()->id);
        $this->assertSame($main->id, $selection->main()->id);
        $this->assertSame($dessert->id, $selection->dessert()->id);
    }

    public function test_preload_caches_the_recipes_by_meal_type(): void
    {
        $selection = RecipeDailySelection::factory()->create();

        $preloaded = $selection->preload();

        $this->assertInstanceOf(Recipe::class, $preloaded->starter());
        $this->assertInstanceOf(Recipe::class, $preloaded->main());
        $this->assertInstanceOf(Recipe::class, $preloaded->dessert());
    }
}
