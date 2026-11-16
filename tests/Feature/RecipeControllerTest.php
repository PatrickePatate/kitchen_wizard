<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_when_viewing_a_recipe_without_a_share_token(): void
    {
        $recipe = Recipe::factory()->create();

        $response = $this->get(route('recipe', ['recipe' => $recipe]));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_view_a_recipe(): void
    {
        $recipe = Recipe::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('recipe', ['recipe' => $recipe]));

        $response->assertOk();
    }

    public function test_guests_can_view_a_recipe_with_a_valid_share_token(): void
    {
        $recipe = Recipe::factory()->create();
        $sharer = User::factory()->create();
        $share = $recipe->shares()->create(['sharer_id' => $sharer->id, 'share_token' => 'valid-token']);

        $response = $this->get(route('recipe', ['recipe' => $recipe, 'share_token' => 'valid-token']));

        $response->assertOk();
    }

    public function test_guests_with_an_invalid_share_token_are_redirected_to_login(): void
    {
        $recipe = Recipe::factory()->create();

        $response = $this->get(route('recipe', ['recipe' => $recipe, 'share_token' => 'invalid-token']));

        $response->assertRedirect(route('login'));
    }

    public function test_guests_cannot_create_a_share_link(): void
    {
        $recipe = Recipe::factory()->create();

        $response = $this->get(route('recipe.api.share', ['recipe' => $recipe]));

        $response->assertRedirect(route('login'));
    }

    public function test_an_authenticated_user_can_create_a_share_link(): void
    {
        $recipe = Recipe::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('recipe.api.share', ['recipe' => $recipe]));

        $response->assertOk();
        $response->assertJsonStructure(['url']);
        $this->assertDatabaseHas('recipe_shares', ['recipe_id' => $recipe->id, 'sharer_id' => $user->id]);
    }

    public function test_guests_are_redirected_to_login_from_search(): void
    {
        $response = $this->get(route('search'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_search_recipes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('search', ['query' => 'pasta']));

        $response->assertOk();
    }

    public function test_search_accepts_a_difficulty_filter(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('search', ['difficulty' => 'facile']));

        $response->assertOk();
        $response->assertViewHas('difficulty', 'facile');
    }

    public function test_search_accepts_a_duration_filter(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('search', ['duration' => 'up_to_30_min']));

        $response->assertOk();
        $response->assertViewHas('duration', 'up_to_30_min');
    }

    public function test_search_ignores_an_invalid_difficulty_filter(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('search', ['difficulty' => 'not-a-real-difficulty']));

        $response->assertOk();
        $response->assertViewHas('difficulty', null);
    }

    public function test_search_ignores_an_invalid_duration_filter(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('search', ['duration' => 'not-a-real-duration']));

        $response->assertOk();
        $response->assertViewHas('duration', null);
    }
}
