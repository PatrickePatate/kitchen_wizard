<?php

namespace App\Services;

use App\MealDifficultyEnum;
use App\MealTypeEnum;
use App\Models\Recipe;
use App\Sharp\Recipes\Filters\DifficultyFilter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Season\Season;
use Storage;

class RecipeSelectorService
{
    public function __construct() {}

    public function getRecipe(MealTypeEnum $type, ?array $avoid=null): ?Recipe
    {
        $month = match(Carbon::today()->month) {
            1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril",
            5 => "Mai", 6 => "Juin", 7 => "Juillet", 8 => "Août",
            9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre",
        };

        $season = match(Carbon::today()->month) {
            12, 1, 2 => 'winter',
            3, 4, 5 => 'spring',
            6, 7, 8 => 'summer',
            9, 10, 11 => 'autumn',
        };


        $seasonalIngredients = json_decode(Storage::disk('resources')->get('json/seasons.json') ?? '[]', true);
        $driver = DB::getDriverName(); // mysql, pgsql, etc.

        $query = Recipe::where('meal_type', $type)
            ->whereNot('published', false)
            ->whereNot('difficulty', MealDifficultyEnum::HARD)
            ->when($month !== 'Décembre', function ($query) use ($driver) {
                $query->where(function ($subQuery) use ($driver) {
                    $subQuery
                        ->whereRaw('LOWER(title) NOT LIKE ?', ['%noël%'])
                        ->whereRaw('LOWER(title) NOT LIKE ?', ['%christmas%']);

                    if ($driver === 'pgsql') {
                        $subQuery
                            ->where('steps', 'NOT ILIKE', '%noël%')
                            ->where('steps', 'NOT ILIKE', '%christmas%');
                    } else {
                        $subQuery
                            ->whereRaw('LOWER(steps) NOT LIKE ?', ['%noël%'])
                            ->whereRaw('LOWER(steps) NOT LIKE ?', ['%christmas%']);
                    }
                });
            }, function ($query) use ($driver) {
                if(Carbon::today()->day == 24 || Carbon::today()->day == 25) {
                    $query->where(function ($subQuery) use ($driver) {
                        $subQuery
                            ->orWhereRaw('LOWER(title) LIKE ?', ['%noël%'])
                            ->orWhereRaw('LOWER(title) LIKE ?', ['%noel%'])
                            ->orWhereRaw('LOWER(title) LIKE ?', ['%christmas%']);

                        if ($driver === 'pgsql') {
                            $subQuery
                                ->orWhere('steps', 'ILIKE', '%noël%')
                                ->orWhere('steps', 'ILIKE', '%christmas%');
                        } else {
                            $subQuery
                                ->orWhereRaw('LOWER(steps) LIKE ?', ['%noël%'])
                                ->orWhereRaw('LOWER(steps) LIKE ?', ['%christmas%']);
                        }
                    });
                }
            })
            ->when($avoid, fn($q) => $q->whereNotIn('id', $avoid));

        // Boost season match: try to get seasonal recipes first
        $seasonal = (clone $query)
            ->where('seasonality', $season)
            ->inRandomOrder()
            ->first();

        if ($seasonal) {
            return $seasonal;
        }

        // Fallback: random if no seasonal recipe matched
        return $query->inRandomOrder()->first();
    }


}
