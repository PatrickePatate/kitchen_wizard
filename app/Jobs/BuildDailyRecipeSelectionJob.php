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
    public function handle(): void
    {
        User::all()->each(function (User $user) {
            $this->buildDailySelection($user);
        });

    }

    private function buildDailySelection(User $user): void
    {
        $selection = new RecipeDailySelection();
        $selection->user_id = $user->id;
        $selection->recipes_selection = [
            'starter' => app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::STARTER)?->id,
            'main' => app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::MAIN_COURSE)?->id,
            'dessert' => app(RecipeSelectorService::class)->getRecipe(MealTypeEnum::DESSERT)?->id,
        ];
        $selection->save();
    }
}
