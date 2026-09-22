<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
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

        $recipes = Recipe::search($request->input('query'))
            ->when($diet, fn ($search) => $search->where('diet', $diet))
            ->paginate(10)
            ->appends($request->only(['query', 'diet']));

        return view('search', ['recipes' => $recipes, 'diet' => $diet]);
    }
}
