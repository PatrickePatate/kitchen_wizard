<?php

namespace App\Sharp\Recipes\Filters;

use App\MealTypeEnum;
use Code16\Sharp\EntityList\Filters\EntityListSelectFilter;
use Illuminate\Support\Str;

class MealTypeFilter extends EntityListSelectFilter
{
    public function buildFilterConfig(): void
    {
        $this->configureLabel(__('Meal type'));
    }

    public function values(): array
    {
        $types = [];
        foreach (MealTypeEnum::cases() as $type) {
            $types[$type->value] = Str::ucfirst($type->value);
        }
       return $types;
    }
}
