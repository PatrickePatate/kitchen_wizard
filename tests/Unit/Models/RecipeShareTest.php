<?php

namespace Tests\Unit\Models;

use App\Models\Recipe;
use App\Models\RecipeShare;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_a_sharer_and_a_recipe(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $share = RecipeShare::factory()->create(['sharer_id' => $user->id, 'recipe_id' => $recipe->id]);

        $this->assertTrue($share->sharer->is($user));
        $this->assertTrue($share->recipe->is($recipe));
    }

    public function test_prunable_selects_shares_older_than_two_months(): void
    {
        $old = RecipeShare::factory()->create(['created_at' => now()->subMonths(3)]);
        $recent = RecipeShare::factory()->create(['created_at' => now()]);

        $prunable = (new RecipeShare())->prunable()->pluck('id');

        $this->assertTrue($prunable->contains($old->id));
        $this->assertFalse($prunable->contains($recent->id));
    }
}
