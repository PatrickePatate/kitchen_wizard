<?php

namespace App\Livewire\Actions;

use App\Models\Recipe;
use Livewire\Component;

class LikeRecipe extends Component
{
    public Recipe $recipe;

    public function mount(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    public function like()
    {
        $this->recipe->likes()->where('user_id', auth()->id())->where('recipe_id', $this->recipe->id)->firstOrCreate([
            'liked_at' => now(),
            'user_id' => auth()->id(),
            'recipe_id' => $this->recipe->id,
        ]);
    }

    public function unlike()
    {
        $this->recipe->likes()->where('user_id', auth()->id())->where('recipe_id', $this->recipe->id)->delete();
    }

    public function render()
    {
        return view('livewire.actions.like-recipe');
    }
}
