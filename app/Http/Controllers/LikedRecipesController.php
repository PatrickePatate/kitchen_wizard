<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikedRecipesController extends Controller
{
    public function index(Request $request)
    {
        return view('likes.likes', [
            'recipes' => auth()->user()->likedRecipes()->paginate(10),
        ]);
    }
}
