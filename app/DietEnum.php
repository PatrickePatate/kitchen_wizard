<?php

namespace App;

enum DietEnum: string
{
    case VEGAN = 'vegan';
    case VEGETARIAN = 'vegetarian';
    case CLASSIC = 'classic';

    public function getLabel(): string
    {
        return match ($this) {
            self::VEGAN => trans('diet.vegan'),
            self::VEGETARIAN => trans('diet.vegetarian'),
            self::CLASSIC => trans('diet.classic'),
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::VEGAN => 'tabler-leaf',
            self::VEGETARIAN => 'tabler-salad',
            self::CLASSIC => 'tabler-meat',
        };
    }
}
