<?php

namespace App\Livewire;

use App\MealTypeEnum;
use App\Models\Recipe;
use App\Services\RecipeSelectorService;
use Livewire\Component;

class RecipeFeed extends Component
{
    public Recipe $main;
    public Recipe $starter;
    public Recipe $dessert;

    public function mount(){
        $this->main = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::MAIN_COURSE);
        $this->starter = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::STARTER);
        $this->dessert = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::DESSERT);
    }

    public function refreshMeal(MealTypeEnum $type){
        if($type == MealTypeEnum::MAIN_COURSE){
            $this->main = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::MAIN_COURSE);
        }elseif($type == MealTypeEnum::STARTER){
            $this->starter = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::STARTER);
        }elseif($type == MealTypeEnum::DESSERT){
            $this->dessert = app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::DESSERT);
        }
    }

    public function render()
    {
        return view('livewire.recipe-feed');
    }
}
