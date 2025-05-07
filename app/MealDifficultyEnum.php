<?php

namespace App;

enum MealDifficultyEnum: string
{
    case VERY_EASY = "très facile";
    case EASY = "facile";
    case MEDIUM = "moyenne";
    case HARD = "difficile";

    public function getLabel(): string
    {
        return match ($this) {
            self::HARD => trans("meal_difficulty.hard"),
            self::MEDIUM => trans("meal_difficulty.medium"),
            self::EASY => trans("meal_difficulty.easy"),
            self::VERY_EASY => trans("meal_difficulty.very_easy"),
        };
    }
}
