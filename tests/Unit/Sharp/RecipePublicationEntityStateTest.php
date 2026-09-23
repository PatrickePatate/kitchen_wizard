<?php

namespace Tests\Unit\Sharp;

use App\Models\Recipe;
use App\Sharp\Recipes\Commands\RecipePublicationEntityState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipePublicationEntityStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_declares_a_published_and_a_draft_state(): void
    {
        $states = app(RecipePublicationEntityState::class)->states();

        $this->assertSame(['published', 'draft'], array_keys($states));
    }

    public function test_it_unpublishes_a_recipe(): void
    {
        $recipe = Recipe::factory()->create(['published' => true]);

        app(RecipePublicationEntityState::class)->execute($recipe->id, ['value' => 'draft']);

        $this->assertFalse($recipe->fresh()->published);
    }

    public function test_it_republishes_a_recipe(): void
    {
        $recipe = Recipe::factory()->create(['published' => false]);

        app(RecipePublicationEntityState::class)->execute($recipe->id, ['value' => 'published']);

        $this->assertTrue($recipe->fresh()->published);
    }
}
