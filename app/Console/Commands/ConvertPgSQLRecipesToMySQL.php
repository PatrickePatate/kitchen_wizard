<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ConvertPgSQLRecipesToMySQL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert-recipes:pgsql-to-mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Recipe::all()->chunk(100)->each(function(Collection $recipes) {
            $recipes->transform(function($recipe) {
                return [
                    'id' => $recipe->id,
                    'title' => $recipe->title,
                    'url' => $recipe->url,
                    'pictures' => json_encode($recipe->pictures ?? []),
                    'total_time' => $recipe->total_time,
                    'times' => json_encode($recipe->times ?? []),
                    'people' => $recipe->people,
                    'difficulty' => $recipe->difficulty?->value,
                    'price' => $recipe->price,
                    'meal_type' => $recipe->meal_type,
                    'ingredients' => json_encode($recipe->ingredients ?? []),
                    'utensils' => json_encode($recipe->utensils ?? []),
                    'steps' => json_encode($recipe->steps ?? []),
                    'author' => $recipe->author,
                    'author_note' => $recipe->author_note,
                    'scrapped_at' => $recipe->scrapped_at,
                ];
            });
            DB::connection('mysql')->table('recipes')->insert($recipes->toArray());
        });
    }
}
