<?php

namespace App\Http\Controllers;

use App\Models\RecipeDailySelection;

class ShoppingListController extends Controller
{
    public function index()
    {
        return view('shopping-list.index', [
            'hasRecipes' => auth()->user()->shoppingListRecipes()->exists(),
        ]);
    }

    public function addTodaySelection()
    {
        $selection = RecipeDailySelection::forUser(auth()->user())?->preload();
        $selection?->addAllToShoppingListFor(auth()->user());

        return back();
    }

    public function clear()
    {
        $user = auth()->user();
        $user->shoppingListRecipes()->delete();
        $user->shoppingListCheckedItems()->delete();

        return back();
    }
}
