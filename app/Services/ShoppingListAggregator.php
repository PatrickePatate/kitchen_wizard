<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Support\Collection;

class ShoppingListAggregator
{
    /**
     * @param Collection<int, Recipe> $recipes
     * @return array<int, array{key: string, label: string, quantity_text: string, recipe_titles: array<int, string>}>
     */
    public function aggregate(Collection $recipes): array
    {
        $groups = [];

        foreach ($recipes as $recipe) {
            foreach ((array) $recipe->ingredients as $ingredient) {
                $label = trim((string) ($ingredient['label'] ?? ''));
                $quantity = $ingredient['quantity'] ?? null;
                $quantityText = trim((string) ($ingredient['quantity_text'] ?? ''));

                if ($label === '') {
                    continue;
                }

                $key = mb_strtolower($label);
                $unit = $this->extractUnit($quantity, $quantityText);

                $groups[$key] ??= [
                    'label' => $label,
                    'quantities' => [], // unit => summed quantity
                    'raw_texts' => [], // deduped free-text fragments (no reliable quantity to sum)
                    'recipe_titles' => [],
                ];

                if ($unit !== null && (float) $quantity > 0) {
                    $groups[$key]['quantities'][$unit] = ($groups[$key]['quantities'][$unit] ?? 0) + (float) $quantity;
                } elseif ($quantityText !== '') {
                    $groups[$key]['raw_texts'][$quantityText] = true;
                }

                $groups[$key]['recipe_titles'][$recipe->title] = true;
            }
        }

        $result = [];

        foreach ($groups as $key => $group) {
            $parts = [];

            foreach ($group['quantities'] as $unit => $sum) {
                $parts[] = $this->formatQuantity($sum).$unit;
            }

            $parts = array_merge($parts, array_keys($group['raw_texts']));

            $result[] = [
                'key' => $key,
                'label' => $group['label'],
                'quantity_text' => implode(', ', $parts),
                'recipe_titles' => array_keys($group['recipe_titles']),
            ];
        }

        usort($result, fn (array $a, array $b) => mb_strtolower($a['label']) <=> mb_strtolower($b['label']));

        return $result;
    }

    private function extractUnit(mixed $quantity, string $quantityText): ?string
    {
        if ($quantity === null || $quantityText === '') {
            return null;
        }

        $quantityPrefix = (string) (is_int($quantity) || (is_float($quantity) && $quantity == (int) $quantity) ? (int) $quantity : $quantity);

        if (!str_starts_with($quantityText, $quantityPrefix)) {
            return null;
        }

        return substr($quantityText, strlen($quantityPrefix));
    }

    private function formatQuantity(float $quantity): string
    {
        return $quantity == (int) $quantity ? (string) (int) $quantity : (string) $quantity;
    }
}
