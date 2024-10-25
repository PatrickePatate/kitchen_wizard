<?php

namespace App\Sharp\Recipes;

use App\Models\Recipe;
use App\Sharp\Recipes\Filters\DifficultyFilter;
use App\Sharp\Recipes\Filters\MealTypeFilter;
use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\Fields\EntityListFieldsLayout;
use Code16\Sharp\EntityList\SharpEntityList;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class RecipesList extends SharpEntityList
{
    protected function buildList(EntityListFieldsContainer $fields): void
    {
        $fields
            ->addField(
                EntityListField::make('photo')
                    ->setLabel(__('Photo'))
                    ->setWidth(1)
            )
            ->addField(
                EntityListField::make('title')
                    ->setLabel(__('Title'))
                    ->setWidth(4)
            )
            ->addField(
                EntityListField::make('meal_type')
                    ->setLabel(__('Meal Type'))
            )
            ->addField(
                EntityListField::make('total_time')
                    ->setLabel(__('Total Time'))
            )
            ->addField(
                EntityListField::make('difficulty')
                    ->setLabel(__('Difficulty'))
            )
            ->addField(
                EntityListField::make('price')
                    ->setLabel(__('Price'))
            );
    }

    public function buildListConfig(): void
    {
        $this
            ->configureSearchable()
            ->configurePaginated();
    }

    protected function getInstanceCommands(): ?array
    {
        return [];
    }

    protected function getEntityCommands(): ?array
    {
        return [];
    }

    protected function getFilters(): array
    {
        return [
            MealTypeFilter::class,
            DifficultyFilter::class,
        ];
    }

    public function getListData(): array|Arrayable
    {
        return $this
            ->setCustomTransformer('photo', function($value, Recipe $recipe) {
                return isset($recipe->pictures[0])
                    ? sprintf('<img src="%s" style="max-height: 50px;" />', asset('storage/'.$recipe->pictures[0]))
                    : null;
            })
            ->setCustomTransformer('meal_type', fn($value) => Str::ucfirst($value))
            ->setCustomTransformer('difficulty', fn($value) => Str::ucfirst($value))
            ->setCustomTransformer('price', fn($value) => Str::ucfirst($value))
            ->transform(
                Recipe::query()
                    ->when($this->queryParams->hasSearch(), function($query) {
                        foreach ($this->queryParams->searchWords() as $word) {
                            $query->where('title', 'like', $word);
                        }
                    })
                    ->when($this->queryParams->filterFor(MealTypeFilter::class), fn($query) => $query->where('meal_type', $this->queryParams->filterFor(MealTypeFilter::class)))
                    ->when($this->queryParams->filterFor(DifficultyFilter::class), fn($query) => $query->where('difficulty', $this->queryParams->filterFor(DifficultyFilter::class)))
                    ->paginate(30)
            );
    }
}
