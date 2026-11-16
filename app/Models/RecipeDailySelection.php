<?php

namespace App\Models;

use App\MealTypeEnum;
use App\Models\Miscs\ShoppingListRecipe;
use App\Services\RecipeSelectorService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeDailySelection extends Model
{
    use HasFactory, Prunable;
    protected $guarded = [];

    protected $recipes = [];

    protected $casts = [
        'recipes_selection' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function forUser(User $user): ?RecipeDailySelection
    {
        return RecipeDailySelection::where('user_id', $user->id)
            ->whereDay('created_at', today())
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
        $selection[$key] = app(RecipeSelectorService::class)->getRecipe($type, [$selection[$key]] ?? null, $this->user?->preferred_diet)->id;
        $this->update(['recipes_selection' => $selection]);
    }

    public function preload(): self
    {
        $recipes = Recipe::whereIn('id', collect($this->recipes_selection)->values())->get();
        $this->recipes['starter'] = $recipes->firstWhere('meal_type', MealTypeEnum::STARTER);
        $this->recipes['main'] = $recipes->firstWhere('meal_type', MealTypeEnum::MAIN_COURSE);
        $this->recipes['dessert'] = $recipes->firstWhere('meal_type', MealTypeEnum::DESSERT);

        return $this;
    }

    public function starter(): ?Recipe
    {
        return $this->recipes['starter'] ?? Recipe::find($this->recipes_selection['starter']);
    }

    public function main(): ?Recipe
    {
        return $this->recipes['main'] ?? Recipe::find($this->recipes_selection['main']);
    }

    public function dessert(): ?Recipe
    {
        return $this->recipes['dessert'] ??Recipe::find($this->recipes_selection['dessert']);
    }


    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonth());
    }

    public function addAllToShoppingListFor(User $user): void
    {
        foreach ([$this->starter(), $this->main(), $this->dessert()] as $recipe) {
            if ($recipe === null) {
                continue;
            }

            ShoppingListRecipe::firstOrCreate([
                'recipe_id' => $recipe->id,
                'user_id' => $user->id,
            ], [
                'added_at' => now(),
            ]);
        }
    }
}
