<?php

namespace App\Sharp;

use Code16\Sharp\Utils\Menu\SharpMenu;

class SharpKitchenMenu extends SharpMenu
{

    public function build(): SharpMenu
    {
        return $this
            ->addEntityLink('users', __('Users'), 'fa-users')
            ->addEntityLink('recipes', __('Recipes'), 'fa-cutlery');
    }
}
