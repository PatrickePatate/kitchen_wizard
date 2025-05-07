<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CalculateSeasonalityCommand extends Command
{

    protected $signature = 'recipes:calculate-seasonality';

    protected $description = 'Calculates the seasonality of recipes.';

    public function handle()
    {
        $months = [
            1 => "Janvier",
            2 => "Février",
            3 => "Mars",
            4 => "Avril",
            5 => "Mai",
            6 => "Juin",
            7 => "Juillet",
            8 => "Août",
            9 => "Septembre",
            10 => "Octobre",
            11 => "Novembre",
            12 => "Décembre",
        ];

        $seasonalData = json_decode(Storage::disk('resources')->get('json/seasons.json') ?? '[]', true);

        Recipe::chunk(100, function ($recipes) use ($months, $seasonalData) {
            foreach ($recipes as $recipe) {
                $ingredients = collect($recipe->ingredients)->pluck('singular');
                $scores = [];

                foreach ($months as $monthNum => $monthName) {
                    $seasonalIngredients = collect($seasonalData[$monthName] ?? [])
                        ->map(fn($i) => mb_strtolower($i));

                    $matches = 0;
                    foreach ($seasonalIngredients as $seasonalIngredient) {
                        if (Str::contains($ingredients->join(' | '), $seasonalIngredient)) {
                            $matches++;
                        }
                    }

                    $scores[$monthName] = $matches;
                }

                // Détermination du mois avec le plus haut score
                $bestMonth = collect($scores)->sortDesc()->keys()->first();
                $bestScore = $scores[$bestMonth] ?? 0;

                if ($bestScore < 2) {
                    $recipe->seasonality = 'unknown';
                } else {
                    $season =  match($bestMonth) {
                        'Décembre',
                        'Janvier',
                        'Février' => 'winter',
                        'Mars',
                        'Avril',
                        'Mai' => 'spring',
                        'Juin',
                        'Juillet',
                        'Août' => 'summer',
                        'Septembre',
                        'Octobre',
                        'Novembre' => 'autumn',
                    };

                    $recipe->seasonality = $season;
                    $this->info("Recipe: {$recipe->title} - Meilleure saison: {$season} (mois: {$bestMonth}, score: $bestScore)");
                }

                $recipe->save();
            }
        });


    }
}
