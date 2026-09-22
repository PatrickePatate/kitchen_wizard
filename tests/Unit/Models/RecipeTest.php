<?php

namespace Tests\Unit\Models;

use App\Models\Recipe;
use App\Models\User;
use App\RecipeDurationEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_isLikedBy_is_false_when_the_user_has_not_liked_the_recipe(): void
    {
        $recipe = Recipe::factory()->create();
        $user = User::factory()->create();

        $this->assertFalse($recipe->isLikedBy($user));
    }

    public function test_isLikedBy_is_true_once_the_user_liked_the_recipe(): void
    {
        $recipe = Recipe::factory()->create();
        $user = User::factory()->create();

        $recipe->likes()->create(['user_id' => $user->id, 'liked_at' => now()]);

        $this->assertTrue($recipe->fresh()->isLikedBy($user));
    }

    public function test_url_attribute_points_to_the_recipe_route(): void
    {
        $recipe = Recipe::factory()->create();

        $this->assertSame(route('recipe', ['recipe' => $recipe]), $recipe->url);
    }

    public function test_share_returns_null_when_there_is_no_authenticated_user(): void
    {
        $recipe = Recipe::factory()->create();

        $this->assertNull($recipe->share());
    }

    public function test_share_creates_a_recipe_share_for_the_authenticated_user(): void
    {
        $recipe = Recipe::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $url = $recipe->share();

        $this->assertNotNull($url);
        $this->assertDatabaseHas('recipe_shares', [
            'recipe_id' => $recipe->id,
            'sharer_id' => $user->id,
        ]);
        $this->assertStringContainsString('share_token=', $url);
    }

    public function test_duration_minutes_is_parsed_from_total_time(): void
    {
        $recipe = Recipe::factory()->create(['total_time' => '1 h 30 min']);

        $this->assertSame(90, $recipe->duration_minutes);
    }

    public function test_duration_bucket_matches_the_recipe_duration(): void
    {
        $recipe = Recipe::factory()->create(['total_time' => '1 h 30 min']);

        $this->assertSame(RecipeDurationEnum::UP_TO_2_HOURS, $recipe->duration_bucket);
    }

    public function test_searchable_array_includes_duration_minutes_and_bucket(): void
    {
        $recipe = Recipe::factory()->create(['total_time' => '20 min']);

        $searchable = $recipe->toSearchableArray();

        $this->assertSame(20, $searchable['duration_minutes']);
        $this->assertSame(RecipeDurationEnum::UP_TO_30_MIN->value, $searchable['duration_bucket']);
    }
}
