<?php

namespace App\Livewire;

use App\Models\Miscs\ShoppingListCheckedItem;
use App\Services\ShoppingListAggregator;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShoppingList extends Component
{
    public Collection $shoppingListRecipes;
    public array $items = [];
    public array $checkedKeys = [];

    public function mount()
    {
        $this->refreshState();
    }

    public function toggleItem(string $key)
    {
        $existing = ShoppingListCheckedItem::where('user_id', auth()->id())->where('item_key', $key)->first();

        if ($existing) {
            $existing->delete();
        } else {
            ShoppingListCheckedItem::create([
                'user_id' => auth()->id(),
                'item_key' => $key,
                'checked_at' => now(),
            ]);
        }

        $this->refreshState();
    }

    public function removeRecipe(int $recipeId)
    {
        auth()->user()->shoppingListRecipes()->where('recipe_id', $recipeId)->delete();

        $this->refreshState();
    }

    public function clear()
    {
        auth()->user()->shoppingListRecipes()->delete();
        auth()->user()->shoppingListCheckedItems()->delete();

        $this->refreshState();
    }

    private function refreshState(): void
    {
        $this->shoppingListRecipes = auth()->user()->shoppingListRecipes()->with('recipe')->get();
        $this->items = app(ShoppingListAggregator::class)->aggregate($this->shoppingListRecipes->pluck('recipe')->filter());
        $this->checkedKeys = auth()->user()->shoppingListCheckedItems()->pluck('item_key')->all();
    }

    public function render()
    {
        return view('livewire.shopping-list');
    }
}
