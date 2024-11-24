<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class TestRecipeController extends Controller
{
    public function show(Request $request)
    {
        $recipes = Recipe::query()
            ->paginate(20);

        return view('test', [
            'recipes' => $recipes,
        ]);
    }
}
