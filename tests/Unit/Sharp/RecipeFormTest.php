<?php

namespace Tests\Unit\Sharp;

use App\Models\Recipe;
use App\Sharp\Recipes\RecipeForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_exposes_flat_times_fields_and_synthetic_ids_on_lists(): void
    {
        $recipe = Recipe::factory()->create([
            'times' => ['prep' => '10 min', 'cook' => '20 min', 'rest_time' => '-'],
            'ingredients' => [
                ['label' => 'Farine', 'quantity' => '125', 'quantity_unit' => 'g', 'quantity_text' => '125 g', 'singular' => 'farine'],
            ],
            'pictures' => ['recipes/1/picture.webp'],
        ]);

        $data = app(RecipeForm::class)->find($recipe->id);

        $this->assertSame('10 min', $data['times_prep']);
        $this->assertSame('20 min', $data['times_cook']);
        $this->assertSame('-', $data['times_rest_time']);
        $this->assertSame(['id' => 0, 'label' => 'Farine', 'quantity' => '125', 'quantity_unit' => 'g', 'quantity_text' => '125 g', 'singular' => 'farine'], $data['ingredients'][0]);
        $this->assertSame(['id' => 0, 'url' => 'recipes/1/picture.webp'], $data['pictures'][0]);
    }

    public function test_find_returns_the_raw_source_url_and_not_the_recipe_route(): void
    {
        // Recipe::url() is a computed accessor (the recipe's internal route)
        // that shadows the real "url" DB column of the same name: find() must
        // read the actual scraped source URL for editing, not the route.
        $recipe = Recipe::factory()->create(['url' => 'https://www.marmiton.org/recettes/some-recipe.aspx']);

        $data = app(RecipeForm::class)->find($recipe->id);

        $this->assertSame('https://www.marmiton.org/recettes/some-recipe.aspx', $data['url']);
    }

    public function test_update_saves_simple_fields_and_rebuilds_times(): void
    {
        $recipe = Recipe::factory()->create();

        app(RecipeForm::class)->update($recipe->id, [
            'title' => 'Nouveau titre',
            'url' => $recipe->getRawOriginal('url'),
            'total_time' => '15 min',
            'times_prep' => '5 min',
            'times_cook' => '10 min',
            'times_rest_time' => '-',
            'price' => 'bon marché',
            'people' => 4,
            'author' => 'Auteur test',
            'author_note' => null,
            'meal_type' => null,
            'difficulty' => null,
            'diet' => null,
            'seasonality' => null,
            'pictures' => [],
            'ingredients' => [],
            'utensils' => [],
            'steps' => [],
        ]);

        $recipe->refresh();

        $this->assertSame('Nouveau titre', $recipe->title);
        $this->assertSame(['prep' => '5 min', 'cook' => '10 min', 'rest_time' => '-'], $recipe->times);
    }

    public function test_update_preserves_ingredient_keys_not_exposed_in_the_form(): void
    {
        $recipe = Recipe::factory()->create([
            'ingredients' => [
                ['label' => 'Farine', 'quantity' => '125', 'quantity_unit' => 'g', 'quantity_text' => '125 g', 'singular' => 'farine'],
            ],
            'utensils' => [
                ['name' => 'saladier', 'label' => '1 saladier'],
            ],
        ]);

        app(RecipeForm::class)->update($recipe->id, [
            'title' => $recipe->title,
            'url' => $recipe->getRawOriginal('url'),
            'times_prep' => null,
            'times_cook' => null,
            'times_rest_time' => null,
            'pictures' => [],
            'ingredients' => [
                ['id' => 0, 'label' => 'Farine complète', 'quantity_text' => '130 g'],
            ],
            'utensils' => [
                ['id' => 0, 'label' => '1 grand saladier'],
            ],
            'steps' => [],
        ]);

        $recipe->refresh();

        $this->assertSame([
            'label' => 'Farine complète',
            'quantity' => '125',
            'quantity_unit' => 'g',
            'quantity_text' => '130 g',
            'singular' => 'farine',
        ], $recipe->ingredients[0]);

        $this->assertSame([
            'name' => 'saladier',
            'label' => '1 grand saladier',
        ], $recipe->utensils[0]);
    }

    public function test_update_does_not_carry_over_hidden_keys_for_newly_added_items(): void
    {
        $recipe = Recipe::factory()->create(['ingredients' => []]);

        app(RecipeForm::class)->update($recipe->id, [
            'title' => $recipe->title,
            'url' => $recipe->getRawOriginal('url'),
            'times_prep' => null,
            'times_cook' => null,
            'times_rest_time' => null,
            'pictures' => [],
            'ingredients' => [
                ['id' => 0, 'label' => 'Sel', 'quantity_text' => '1 pincée'],
            ],
            'utensils' => [],
            'steps' => [],
        ]);

        $recipe->refresh();

        $this->assertSame(['label' => 'Sel', 'quantity_text' => '1 pincée'], $recipe->ingredients[0]);
    }

    public function test_update_extracts_plain_urls_from_the_pictures_list(): void
    {
        $recipe = Recipe::factory()->create(['pictures' => ['old.webp']]);

        app(RecipeForm::class)->update($recipe->id, [
            'title' => $recipe->title,
            'url' => $recipe->getRawOriginal('url'),
            'times_prep' => null,
            'times_cook' => null,
            'times_rest_time' => null,
            'pictures' => [
                ['id' => 0, 'url' => 'new.webp'],
            ],
            'ingredients' => [],
            'utensils' => [],
            'steps' => [],
        ]);

        $recipe->refresh();

        $this->assertSame(['new.webp'], $recipe->pictures);
    }
}
