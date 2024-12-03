<?php

namespace App\Livewire;

use App\MealTypeEnum;
use App\Models\Recipe;
use App\Models\RecipeDailySelection;
use App\Services\RecipeSelectorService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class RecipeFeed extends Component
{
    public ?RecipeDailySelection $selection;
    public Collection $lastWeekSelections;
    public ?Recipe $main;
    public ?Recipe $starter;
    public ?Recipe $dessert;
    public ?Carbon $selectionDay;

    public function mount(){
        $this->selection = RecipeDailySelection::forUser(auth()->user())?->preload();
        $this->lastWeekSelections = RecipeDailySelection::query()
            ->where('user_id', auth()->id())
            ->whereDate('created_at', '>=', Carbon::today()->subWeek())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($prevSelection) {
                $prevSelection->created_at = $prevSelection->created_at->setTime(0,0);
                return $prevSelection;
            })->unique('created_at');

        $this->selectionDay = $this->selection?->created_at;

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

    public function selectDay(string $date)
    {
        $this->selectionDay = Carbon::parse($date);
        $this->selection = RecipeDailySelection::query()
            ->where('user_id', auth()->id())
            ->whereDay('created_at', $this->selectionDay)
            ->first()?->preload();

        $this->main = $this->selection?->main();
        $this->starter = $this->selection?->starter();
        $this->dessert = $this->selection?->dessert();
    }

    public function render()
    {
        return view('livewire.recipe-feed');
    }
}
