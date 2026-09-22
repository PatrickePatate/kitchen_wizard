<?php

namespace App\Jobs;

use App\MealTypeEnum;
use App\Models\RecipeDailySelection;
use App\Models\User;
use App\Services\RecipeSelectorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BuildDailyRecipeSelectionJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(RecipeSelectorService $recipeSelector): void
    {
        User::query()->chunkById(100, function ($users) use ($recipeSelector) {
            $users->each(fn (User $user) => $this->buildDailySelection($user, $recipeSelector));
        });
    }

    private function buildDailySelection(User $user, RecipeSelectorService $recipeSelector): void
    {
        $userLastRecipesIds = $user->dailySelections()
            ->latest()
            ->take(7)
            ->get(['recipes_selection'])
            ->pluck('recipes_selection')
            ->flatMap(fn (array $selection) => array_values($selection))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $selection = new RecipeDailySelection();
        $selection->user_id = $user->id;
        $selection->recipes_selection = [
            'starter' => $recipeSelector->getRecipe(MealTypeEnum::STARTER, $userLastRecipesIds, $user->preferred_diet)?->id,
            'main' => $recipeSelector->getRecipe(MealTypeEnum::MAIN_COURSE, $userLastRecipesIds, $user->preferred_diet)?->id,
            'dessert' => $recipeSelector->getRecipe(MealTypeEnum::DESSERT, $userLastRecipesIds, $user->preferred_diet)?->id,
        ];
        $selection->save();
    }
}
