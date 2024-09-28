<?php

namespace App\Http\Controllers;

use App\MealTypeEnum;
use App\Models\Recipe;
use App\Services\RecipeSelectorService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $main = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::MAIN_COURSE);
        $starter = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::STARTER);
        $dessert = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::DESSERT);

        return view('home', [
            'main' => $main,
            'starter' => $starter,
            'dessert' => $dessert,
        ]);
    }
}
