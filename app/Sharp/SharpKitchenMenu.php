<?php

namespace App\Sharp;

use App\Sharp\Entities\RecipeEntity;
use App\Sharp\Entities\UserEntity;
use Code16\Sharp\Utils\Menu\SharpMenu;
use Code16\Sharp\Utils\Menu\SharpMenuItemSection;

class SharpKitchenMenu extends SharpMenu
{

    public function build(): SharpMenu
    {
        return
        $this
            ->addSection("Contenu", function (SharpMenuItemSection $section) {
                $section
                    ->addEntityLink(RecipeEntity::class, __('Recipes'), 'fa-utensils')
                    ->addEntityLink(UserEntity::class, __('Users'), 'fa-users');
            });
    }
}
