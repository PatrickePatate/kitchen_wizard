<?php

namespace App\Support;

class DurationParser
{
    /**
     * Convert a scraped duration string (e.g. "1 h 10 min", "2h30", "1 j 8 min", "-")
     * into a number of minutes, or null when it carries no duration information.
     */
    public static function toMinutes(?string $raw): ?int
    {
        $value = trim((string) $raw);

        if ($value === '' || $value === '-') {
            return null;
        }

        // Compact form used for hour+minutes without separators, e.g. "2h30", "10h05".
        if (preg_match('/^(\d+)h(\d{2})$/u', $value, $matches)) {
            return ((int) $matches[1]) * 60 + (int) $matches[2];
        }

        $minutesPerUnit = [
            'j' => 24 * 60,
            'h' => 60,
            'min' => 1,
            'sec' => 1 / 60,
        ];

        $totalMinutes = 0.0;
        $matched = false;

        foreach ($minutesPerUnit as $unit => $unitInMinutes) {
            if (preg_match('/(\d+)\s*'.$unit.'\b/u', $value, $matches)) {
                $totalMinutes += ((int) $matches[1]) * $unitInMinutes;
                $matched = true;
            }
        }

        return $matched ? (int) round($totalMinutes) : null;
    }
}
