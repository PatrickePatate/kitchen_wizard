<?php

namespace App\Console\Commands\Import;

use App\Models\Recipe;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Storage;

class DownloadRecipePicturesCommand extends Command
{

    protected $signature = 'recipes:download-pictures';


    protected $description = 'Downloads the pictures of the recipes';

    public function handle()
    {
        Recipe::cursor()->each(function (Recipe $recipe) {
            $this->storePicture($recipe);
        });
    }

    private function storePicture(Recipe $recipe): void
    {
        $pictures = $recipe->pictures;
        foreach ($pictures as $url) {
            try{
                $picture = Http::get($url);
                if($picture->failed()) {
                    $this->error("Failed to download picture from '{$url}' for recipe #{$recipe->id}");
                    continue;
                }
                $filename = sprintf('recipes/%s/%s', $recipe->id, basename($url));
                $store = Storage::disk(config('app.assets.disk'))->put(
                    $filename,
                    $picture->getBody()->getContents(),
                );

                if($store) {
                    unset($pictures[array_search($url, $pictures)]);
                    $pictures[] = $filename;
                    $this->info("Picture from '{$url}' for recipe #{$recipe->id} has been downloaded");
                } else {
                    $this->error("Failed to store picture from '{$url}' for recipe #{$recipe->id}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to download picture from '{$url}' for recipe #{$recipe->id}, error message: ".$e->getMessage());
            }
        }
        $recipe->update(['pictures' => array_values($pictures)]);
    }
}
