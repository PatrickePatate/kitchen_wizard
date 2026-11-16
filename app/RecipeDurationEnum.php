<?php

namespace App;

enum RecipeDurationEnum: string
{
    case UP_TO_30_MIN = 'up_to_30_min';
    case UP_TO_1_HOUR = 'up_to_1_hour';
    case UP_TO_2_HOURS = 'up_to_2_hours';
    case OVER_2_HOURS = 'over_2_hours';

    public function getLabel(): string
    {
        return match ($this) {
            self::UP_TO_30_MIN => trans('recipe_duration.up_to_30_min'),
            self::UP_TO_1_HOUR => trans('recipe_duration.up_to_1_hour'),
            self::UP_TO_2_HOURS => trans('recipe_duration.up_to_2_hours'),
            self::OVER_2_HOURS => trans('recipe_duration.over_2_hours'),
        };
    }

    /**
     * Bucket a total duration (in minutes) into one of this enum's cases.
     */
    public static function fromMinutes(?int $minutes): ?self
    {
        if ($minutes === null) {
            return null;
        }

        return match (true) {
            $minutes <= 30 => self::UP_TO_30_MIN,
            $minutes <= 60 => self::UP_TO_1_HOUR,
            $minutes <= 120 => self::UP_TO_2_HOURS,
            default => self::OVER_2_HOURS,
        };
    }
}
