<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Dev-only seeder: randomly fills `diet` and `seasonality` on existing recipes
 * so features relying on them can be built without running the real
 * (slow/LLM-backed) recipes:classify-diet and recipes:calculate-seasonality commands.
 */
class FakeRecipesDevSeeder extends Seeder
{
    private const DIETS = ['vegetarian', 'vegan', 'classic'];

    private const SEASONALITIES = ['spring', 'summer', 'autumn', 'winter', 'unknown'];

    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->error('FakeRecipesDevSeeder refuses to run in production.');
            return;
        }

        $ids = DB::table('recipes')->pluck('id');

        if ($ids->isEmpty()) {
            $this->command?->warn('No recipes found. Run RecipeSeeder first.');
            return;
        }

        // Group ids by every diet/seasonality combination and issue one
        // UPDATE per group (instead of one per row) so this stays fast.
        $buckets = [];

        foreach ($ids as $id) {
            $key = self::DIETS[array_rand(self::DIETS)].'|'.self::SEASONALITIES[array_rand(self::SEASONALITIES)];
            $buckets[$key][] = $id;
        }

        foreach ($buckets as $key => $bucketIds) {
            [$diet, $seasonality] = explode('|', $key);

            foreach (array_chunk($bucketIds, 1000) as $chunk) {
                DB::table('recipes')->whereIn('id', $chunk)->update([
                    'diet' => $diet,
                    'seasonality' => $seasonality,
                ]);
            }
        }

        $this->command?->info("Assigned random diet and seasonality to {$ids->count()} recipes.");
    }
}
