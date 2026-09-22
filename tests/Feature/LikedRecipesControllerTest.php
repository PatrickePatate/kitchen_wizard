<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikedRecipesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get(route('likes'));

        $response->assertRedirect(route('login'));
    }

    public function test_it_lists_only_the_authenticated_users_liked_recipes(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $likedRecipe = Recipe::factory()->create();
        $othersRecipe = Recipe::factory()->create();

        $user->likedRecipes()->create(['recipe_id' => $likedRecipe->id, 'liked_at' => now()]);
        $otherUser->likedRecipes()->create(['recipe_id' => $othersRecipe->id, 'liked_at' => now()]);

        $response = $this->actingAs($user)->get(route('likes'));

        $response->assertOk();
        $response->assertViewHas('recipes', function ($recipes) use ($likedRecipe) {
            return $recipes->total() === 1 && $recipes->first()->recipe_id === $likedRecipe->id;
        });
    }
}
