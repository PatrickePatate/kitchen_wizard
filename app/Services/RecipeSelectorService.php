<?php

namespace App\Services;

use App\MealTypeEnum;
use App\Models\Recipe;
use Carbon\Carbon;
use Season\Season;
use Storage;

class RecipeSelectorService
{
    public function __construct(){

    }

    public function getRecipe(MealTypeEnum $type, ?int $avoid=null): ?Recipe
    {
        $month = match(Carbon::today()->month) {
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
        };


        $seasonalIngredients = json_decode(Storage::disk('resources')->get('json/seasons.json') ?? '[]', true);

        return Recipe::where('meal_type', $type)
            ->whereNot('difficulty', "difficile")
//            ->where('total_time', '<', 45)
            ->where(function ($query) use ($seasonalIngredients, $month) {
                // Boucle sur chaque ingrédient saisonnier et vérifie la clé JSON 'singular'
                foreach ($seasonalIngredients[$month] ?? [] as $ingredient) {
                    $query->orWhere('ingredients', 'LIKE', '%' . $ingredient . '%');
                }
            })
            ->when($avoid, function($query) use ($avoid){
                return $query->where('id', '!=', $avoid);
            })
            ->inRandomOrder()
            ->first();
    }


}
