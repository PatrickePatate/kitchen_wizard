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
        $items = [];

        foreach ($recipes as $recipe) {
            foreach ((array) $recipe->ingredients as $ingredient) {
                $label = trim((string) ($ingredient['label'] ?? ''));
                $quantity = $ingredient['quantity'] ?? null;
                $quantityText = trim((string) ($ingredient['quantity_text'] ?? ''));

                if ($label === '') {
                    continue;
                }

                $unit = $this->extractUnit($quantity, $quantityText);
                $canMerge = $unit !== null && (float) $quantity > 0;

                $key = $canMerge
                    ? mb_strtolower($label).'|'.mb_strtolower(trim($unit))
                    : mb_strtolower($label).'|'.md5($quantityText).'|'.spl_object_id($recipe);

                if (!isset($items[$key])) {
                    $items[$key] = [
                        'key' => $key,
                        'label' => $label,
                        'quantity' => $canMerge ? (float) $quantity : null,
                        'unit' => $canMerge ? $unit : null,
                        'quantity_text' => $quantityText,
                        'recipe_titles' => [],
                    ];
                } elseif ($canMerge) {
                    $items[$key]['quantity'] += (float) $quantity;
                    $items[$key]['quantity_text'] = $this->formatQuantity($items[$key]['quantity']).$items[$key]['unit'];
                }

                $items[$key]['recipe_titles'][] = $recipe->title;
            }
        }

        $result = array_values(array_map(function (array $item) {
            $item['recipe_titles'] = array_values(array_unique($item['recipe_titles']));
            unset($item['quantity'], $item['unit']);

            return $item;
        }, $items));

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
