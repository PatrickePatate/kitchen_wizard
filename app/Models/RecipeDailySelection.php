<?php

namespace App\Models;

use App\MealTypeEnum;
use App\Services\RecipeSelectorService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class RecipeDailySelection extends Model
{
    use Prunable;
    protected $guarded = [];

    protected $casts = [
        'recipes_selection' => 'array'
    ];

    public static function forUser(User $user): ?RecipeDailySelection
    {
        return RecipeDailySelection::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function refreshRecipe(MealTypeEnum $type): void
    {
        $key = match($type) {
            MealTypeEnum::STARTER => 'starter',
            MealTypeEnum::MAIN_COURSE => 'main',
            MealTypeEnum::DESSERT => 'dessert',
        };

        $selection = $this->recipes_selection;
        $selection[$key] = app(RecipeSelectorService::class)->getRecipe($type, $selection[$key] ?? null)->id;
        $this->update(['recipes_selection' => $selection]);
    }

    public function starter(): ?Recipe
    {
        return Recipe::find($this->recipes_selection['starter']);
    }

    public function main(): ?Recipe
    {
        return Recipe::find($this->recipes_selection['main']);
    }

    public function dessert(): ?Recipe
    {
        return Recipe::find($this->recipes_selection['dessert']);
    }


    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}
