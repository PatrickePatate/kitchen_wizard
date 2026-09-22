<?php

namespace App\Console\Commands;

use App\MealTypeEnum;
use App\Models\Recipe;
use Illuminate\Console\Command;
use Laravel\Ai\Classification;
use Laravel\Ai\Classification\Choice;

class ClassifyRecipesDietCommand extends Command
{
    protected $signature = 'recipes:classify-diet
        {--meal-type=main_course : The MealTypeEnum case (in lowercase) to classify, or "all" for every meal type}
        {--force : Reclassify recipes that already have a diet}';

    protected $description = 'Classifies recipes as vegetarian, vegan or classic using AI.';

    public function handle(): void
    {
        $query = Recipe::query();

        if ($this->option('meal-type') !== 'all') {
            $mealType = constant(MealTypeEnum::class . '::' . strtoupper($this->option('meal-type')));

            $query->where('meal_type', $mealType->value);
        }

        if (! $this->option('force')) {
            $query->whereNull('diet');
        }

        $progressBar = $this->output->createProgressBar($query->count());
        $progressBar->start();

        $query->chunkById(50, function ($recipes) use ($progressBar) {
            foreach ($recipes as $recipe) {
                $ingredients = collect($recipe->ingredients)->pluck('singular')->implode(', ');

                $response = Classification::of([
                    'title' => $recipe->title,
                    'ingredients' => $ingredients,
                ])
                    ->question('diet', new Choice(
                        'Classify this recipe\'s diet based on its ingredients.',
                        [
                            'vegan' => 'Contains no animal products at all (no meat, fish, dairy, eggs, honey, etc.).',
                            'vegetarian' => 'Contains no meat or fish, but may contain dairy, eggs, or honey.',
                            'classic' => 'Contains meat or fish.',
                        ],
                    ))
                    ->classify('openrouter', '~typesafe/jev-latest');

                $diet = $response->answer('diet')->choice;

                $recipe->diet = $diet;
                $recipe->save();

                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->newLine();
    }
}
