<?php

namespace Tests\Unit\Models;

use App\Models\Recipe;
use App\Models\User;
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
}
