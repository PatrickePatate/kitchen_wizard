<?php

namespace App\Sharp\Recipes\Filters;

use App\MealTypeEnum;
use App\Models\Recipe;
use App\Sharp\Recipes\RecipesList;
use Code16\Sharp\EntityList\Filters\EntityListSelectFilter;
use Illuminate\Support\Str;

class DifficultyFilter extends EntityListSelectFilter
{
    public function buildFilterConfig(): void
    {
        $this->configureLabel(__('Difficulty'));
    }

    public function values(): array
    {

        return Recipe::distinct('difficulty')
            ->get()
            ->pluck('difficulty')
            ->values()
            ->unique()
            ->mapWithKeys(fn($dif) => [$dif->value => $dif->getLabel()])
            ->toArray();
    }
}
