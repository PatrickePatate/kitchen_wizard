<?php

namespace App\Sharp\Recipes\Commands;

use App\Models\Recipe;
use Code16\Sharp\EntityList\Commands\EntityState;

class RecipePublicationEntityState extends EntityState
{
    protected function buildStates(): void
    {
        $this
            ->addState('published', __('Publiée'), 'green')
            ->addState('draft', __('Dépubliée'), 'grey');
    }

    protected function updateState($instanceId, string $stateId): array
    {
        Recipe::findOrFail($instanceId)->update([
            'published' => $stateId === 'published',
        ]);

        return $this->refresh($instanceId);
    }
}
