<?php

namespace App\Http\Controllers;

use App\MealDifficultyEnum;
use App\Models\Recipe;
use App\RecipeDurationEnum;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function show(Request $request, Recipe $recipe)
    {
        if (!$request->has('share_token')) {
            if(!auth()->check()) {
                return redirect()->route('login');
            }
        } else {
            $shareToken = $request->input('share_token');
            $share = $recipe->shares()->where('share_token', $shareToken)->first();
            if(!$share) {
                return redirect()->route('login');
            }
        }
        return view('recipe', ['recipe' => $recipe, 'share' => $share ?? null]);
    }

    public function share(Request $request, Recipe $recipe) {
        if ($user = auth()->user()) {
            $share = $recipe->share();

            return response()->json(['url' => $share]);
        }

        return redirect()->route('login');
    }

    public function search(Request $request)
    {
        $diet = $request->input('diet');
        $mealType = $request->input('meal_type');
        $difficulty = MealDifficultyEnum::tryFrom((string) $request->input('difficulty'))?->value;
        $duration = RecipeDurationEnum::tryFrom((string) $request->input('duration'))?->value;

        $recipes = Recipe::search($request->input('query'))
            ->where('published', true)
            ->when($diet, fn ($search) => $search->where('diet', $diet))
            ->when($mealType, fn ($search) => $search->where('meal_type', $mealType))
            ->when($difficulty, fn ($search) => $search->where('difficulty', $difficulty))
            ->when($duration, fn ($search) => $search->where('duration_bucket', $duration))
            ->paginate(10)
            ->appends($request->only(['query', 'diet', 'meal_type', 'difficulty', 'duration']));

        return view('search', [
            'recipes' => $recipes,
            'diet' => $diet,
            'mealType' => $mealType,
            'difficulty' => $difficulty,
            'duration' => $duration,
        ]);
    }
}
