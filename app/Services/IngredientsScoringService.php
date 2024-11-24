<?php

namespace App\Services;

use App\Models\Recipe;

class IngredientsScoringService
{
    public function handle()
    {
        $ingredients = Recipe::select('ingredients')->cursor();
        $uniques = collect();

        // Gettings all ingredients from all recipes
        $ingredients->each(function ($recipe) use ($uniques) {
            foreach ($recipe->ingredients as $ingredient) {
                $uniques->push($ingredient['singular']);
            }
        });

        $ingredients = $uniques->unique();
    }
}
