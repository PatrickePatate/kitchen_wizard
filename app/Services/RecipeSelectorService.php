<?php

namespace App\Services;

use App\MealTypeEnum;
use App\Models\Recipe;
use Carbon\Carbon;
use Season\Season;

class RecipeSelectorService
{
    public function __construct(){

    }

    public function getRecipe(MealTypeEnum $type, ?int $avoid=null): Recipe
    {

        return Recipe::where('meal_type', $type)
            ->whereNot('difficulty', "difficile")
            ->where('total_time', '<', 45)
            ->when($avoid, function($query) use ($avoid){
                return $query->where('id', '!=', $avoid);
            })
            ->inRandomOrder()
            ->first();
    }


}
