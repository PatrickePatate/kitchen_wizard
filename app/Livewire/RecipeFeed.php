<?php

namespace App\Livewire;

use App\MealTypeEnum;
use App\Models\Recipe;
use App\Models\RecipeDailySelection;
use App\Services\RecipeSelectorService;
use Livewire\Component;

class RecipeFeed extends Component
{
    public ?RecipeDailySelection $selection;
    public ?Recipe $main;
    public ?Recipe $starter;
    public ?Recipe $dessert;

    public function mount(){
        $this->selection = RecipeDailySelection::forUser(auth()->user())?->preload();

        $this->main = $this->selection?->main();
        $this->starter = $this->selection?->starter();
        $this->dessert = $this->selection?->dessert();
    }

    public function refreshMeal(MealTypeEnum $type){
        $this->selection->refreshRecipe($type);

        $this->main = $this->selection?->main();
        $this->starter = $this->selection?->starter();
        $this->dessert = $this->selection?->dessert();
    }

    public function render()
    {
        return view('livewire.recipe-feed');
    }
}
