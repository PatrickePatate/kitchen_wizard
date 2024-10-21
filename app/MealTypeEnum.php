<?php

namespace App;

enum MealTypeEnum: string
{
    case STARTER = "entrée";
    case MAIN_COURSE = "plat principal";
    case DESSERT = "dessert";
    case SAUCE = "sauce";
    case SIDE = "accompagnement";
    case DRINK = "boisson";
    case APERITIVE = "apéritif";
    case OTHER = "autre";

    public function getIcon(): string
    {
        return match ($this) {
            self::STARTER => 'tabler-salad',
            self::MAIN_COURSE => 'tabler-burger',
            self::DESSERT => 'tabler-ice-cream',
            self::SAUCE => 'tabler-bowl',
            self::SIDE => 'tabler-bowl-spoon',
            self::DRINK,
            self::APERITIVE => 'tabler-glass-cocktail',
            self::OTHER => 'tabler-apple',
        };
    }
}
