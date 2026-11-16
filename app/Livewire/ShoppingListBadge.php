<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ShoppingListBadge extends Component
{
    public int $count = 0;

    public function mount()
    {
        $this->refreshCount();
    }

    #[On('shopping-list-updated')]
    public function refreshCount()
    {
        $this->count = auth()->check() ? auth()->user()->shoppingListRecipes()->count() : 0;
    }

    public function render()
    {
        return view('livewire.shopping-list-badge');
    }
}
