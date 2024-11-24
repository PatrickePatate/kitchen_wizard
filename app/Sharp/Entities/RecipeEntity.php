<?php

namespace App\Sharp\Entities;

use App\Sharp\Recipes\RecipeShow;
use App\Sharp\Recipes\RecipesList;
use Code16\Sharp\Utils\Entities\SharpEntity;

class RecipeEntity extends SharpEntity
{
    public string $label = "Recette";
    protected ?string $list = RecipesList::class;
    protected ?string $show = RecipeShow::class;

    protected array $prohibitedActions = ['create', 'update'];
}
