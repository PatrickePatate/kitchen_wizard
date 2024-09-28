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
}
