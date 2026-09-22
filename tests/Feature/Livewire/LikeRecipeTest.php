<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Actions\LikeRecipe;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LikeRecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_like_a_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        Livewire::actingAs($user)
            ->test(LikeRecipe::class, ['recipe' => $recipe])
            ->call('like');

        $this->assertDatabaseHas('recipe_likes', [
            'recipe_id' => $recipe->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_liking_a_recipe_twice_does_not_create_duplicates(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $component = Livewire::actingAs($user)->test(LikeRecipe::class, ['recipe' => $recipe]);
        $component->call('like');
        $component->call('like');

        $this->assertSame(1, $recipe->likes()->where('user_id', $user->id)->count());
    }

    public function test_a_user_can_unlike_a_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $recipe->likes()->create(['user_id' => $user->id, 'liked_at' => now()]);

        Livewire::actingAs($user)
            ->test(LikeRecipe::class, ['recipe' => $recipe])
            ->call('unlike');

        $this->assertDatabaseMissing('recipe_likes', [
            'recipe_id' => $recipe->id,
            'user_id' => $user->id,
        ]);
    }
}
