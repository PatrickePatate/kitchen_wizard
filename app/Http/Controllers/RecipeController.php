<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function show(Recipe $recipe)
    {
        return view('recipe', ['recipe' => $recipe]);
    }

    public function search(Request $request)
    {
        $recipes = Recipe::search($request->input('query'))->paginate(10);

        return view('search', ['recipes' => $recipes]);
    }
}
