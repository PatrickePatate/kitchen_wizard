<?php

namespace App\Livewire\Actions;

use App\Models\Miscs\ShoppingListRecipe;
use App\Models\Recipe;
use Livewire\Component;

class AddToShoppingList extends Component
{
    public Recipe $recipe;
    public string $wrapperClass = 'absolute right-3 top-14';
    public string $iconClass = 'h-7 w-7 text-white drop-shadow-xl';

    public function mount(Recipe $recipe, string $wrapperClass = 'absolute right-3 top-14', string $iconClass = 'h-7 w-7 text-white drop-shadow-xl')
    {
        $this->recipe = $recipe;
        $this->wrapperClass = $wrapperClass;
        $this->iconClass = $iconClass;
    }

    public function add()
    {
        ShoppingListRecipe::firstOrCreate([
            'user_id' => auth()->id(),
            'recipe_id' => $this->recipe->id,
        ], [
            'added_at' => now(),
        ]);
    }

    public function remove()
    {
        $this->recipe->shoppingListRecipes()->where('user_id', auth()->id())->delete();
    }

    public function render()
    {
        return view('livewire.actions.add-to-shopping-list');
    }
}
