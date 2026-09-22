<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Columns as dumped by `pg_dump` in database/recipes.sql, in order.
     */
    private const COLUMNS = [
        'id', 'title', 'url', 'pictures', 'total_time', 'times', 'difficulty',
        'price', 'meal_type', 'ingredients', 'utensils', 'steps', 'author',
        'author_note', 'scrapped_at', 'people',
    ];

    private const JSON_COLUMNS = ['pictures', 'times', 'ingredients', 'utensils', 'steps'];

    public function run(): void
    {
        $path = database_path('recipes.sql');

        if (!file_exists($path)) {
            $this->command?->warn("Skipping recipe seeding: {$path} not found.");
            return;
        }

        DB::table('recipes')->truncate();

        $handle = fopen($path, 'r');
        $inCopyBlock = false;
        $batch = [];
        $batchSize = 500;
        $total = 0;

        while (($line = fgets($handle)) !== false) {
            if (!$inCopyBlock) {
                if (str_starts_with($line, 'COPY public.recipes ')) {
                    $inCopyBlock = true;
                }
                continue;
            }

            $line = rtrim($line, "\n");

            if ($line === '\.') {
                $inCopyBlock = false;
                continue;
            }

            $batch[] = $this->parseRow($line);

            if (count($batch) >= $batchSize) {
                DB::table('recipes')->insert($batch);
                $total += count($batch);
                $batch = [];
            }
        }

        fclose($handle);

        if (!empty($batch)) {
            DB::table('recipes')->insert($batch);
            $total += count($batch);
        }

        $this->command?->info("Seeded {$total} recipes.");

        $this->syncAutoIncrement();
    }

    /**
     * Rows are inserted with explicit ids, so the driver's auto-increment
     * sequence needs to be pushed past the highest imported id.
     */
    private function syncAutoIncrement(): void
    {
        $driver = DB::connection()->getDriverName();
        $maxId = DB::table('recipes')->max('id');

        if (!$maxId) {
            return;
        }

        match ($driver) {
            'pgsql' => DB::statement(
                "SELECT setval(pg_get_serial_sequence('recipes', 'id'), ?)",
                [$maxId]
            ),
            'mysql', 'mariadb' => DB::statement(
                "ALTER TABLE recipes AUTO_INCREMENT = " . ((int) $maxId + 1)
            ),
            default => null,
        };
    }

    private function parseRow(string $line): array
    {
        $fields = explode("\t", $line);
        $row = [];

        foreach (self::COLUMNS as $i => $column) {
            $value = $this->unescape($fields[$i] ?? '\N');

            if ($value !== null && in_array($column, self::JSON_COLUMNS, true)) {
                // Already valid JSON text from the dump, stored as-is in the json column.
                $value = json_encode(json_decode($value), JSON_UNESCAPED_UNICODE);
            }

            $row[$column] = $value;
        }

        return $row;
    }

    /**
     * Undo PostgreSQL COPY text-format escaping for a single field.
     */
    private function unescape(string $value): ?string
    {
        if ($value === '\N') {
            return null;
        }

        return str_replace(
            ['\\t', '\\n', '\\r', '\\\\'],
            ["\t", "\n", "\r", '\\'],
            $value
        );
    }
}
