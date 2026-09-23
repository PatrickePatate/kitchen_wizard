<?php

namespace App\Sharp\Recipes\Commands;

use App\Ai\RecipeUrlExtractorAgent;
use App\Models\Recipe;
use App\Sharp\Entities\RecipeEntity;
use Code16\Sharp\EntityList\Commands\EntityCommand;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Utils\Fields\FieldsContainer;
use Code16\Sharp\Utils\Links\LinkToShowPage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateRecipeFromUrlCommand extends EntityCommand
{
    public function label(): string
    {
        return __('Créer depuis une URL');
    }

    public function buildCommandConfig(): void
    {
        $this
            ->configureDescription(__('Analyse la page et pré-remplit une nouvelle recette à partir de son contenu.'))
            ->configureFormModalTitle(__('Créer une recette depuis une URL'))
            ->configureFormModalButtonLabel(__('Analyser et créer'));
    }

    public function buildFormFields(FieldsContainer $formFields): void
    {
        $formFields->addField(
            SharpFormTextField::make('url')
                ->setLabel(__('URL de la recette'))
                ->setPlaceholder('https://...')
        );
    }

    public function rules(): array
    {
        return [
            'url' => 'required|url|max:2000',
        ];
    }

    public function execute(array $data = []): array
    {
        $url = $data['url'];

        try {
            // Some sites' WAF (e.g. SiteGround) blocks requests that only set a
            // browser User-Agent but lack the other headers a real browser
            // always sends (Accept, Accept-Language), returning a 403.
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
            ])
                ->timeout(20)
                ->get($url);
        } catch (\Throwable) {
            throw ValidationException::withMessages([
                'url' => __("Impossible de récupérer cette page. Vérifiez l'URL."),
            ]);
        }

        if (! $response->successful()) {
            throw ValidationException::withMessages([
                'url' => __('La page a répondu avec une erreur (:status).', ['status' => $response->status()]),
            ]);
        }

        $pageText = $this->extractReadableText($response->body());

        if (Str::of($pageText)->trim()->isEmpty()) {
            throw ValidationException::withMessages([
                'url' => __('Aucun contenu exploitable trouvé sur cette page.'),
            ]);
        }

        $extracted = (new RecipeUrlExtractorAgent)->prompt(
            "Voici le contenu texte de la page à l'URL {$url} :\n\n{$pageText}"
        );

        $recipe = Recipe::create([
            'url' => $url,
            'title' => $extracted['title'],
            'meal_type' => $extracted['meal_type'],
            'difficulty' => $extracted['difficulty'],
            'diet' => $extracted['diet'],
            'seasonality' => $extracted['seasonality'],
            'total_time' => $extracted['total_time'],
            'times' => [
                'prep' => $extracted['prep_time'],
                'cook' => $extracted['cook_time'],
                'rest_time' => $extracted['rest_time'],
            ],
            'price' => $extracted['price'],
            'people' => $extracted['people'],
            'author' => $extracted['author'],
            'author_note' => $extracted['author_note'],
            'pictures' => [],
            'ingredients' => $extracted['ingredients'],
            'utensils' => $extracted['utensils'],
            'steps' => $extracted['steps'],
            'published' => false,
        ]);

        $recipe->update([
            'pictures' => $this->downloadPictures($recipe, $extracted['pictures']),
        ]);

        return $this->link(
            LinkToShowPage::make(RecipeEntity::class, $recipe->id)->renderAsUrl()
        );
    }

    /**
     * Downloads the extracted picture URLs to the assets disk, following the
     * same convention as App\Console\Commands\Import\DownloadRecipePicturesCommand
     * (recipes/{id}/{basename}), so freshly created recipes store their
     * pictures the same way as previously scraped ones.
     */
    private function downloadPictures(Recipe $recipe, array $urls): array
    {
        $pictures = [];

        foreach ($urls as $url) {
            try {
                $picture = Http::get($url);

                if ($picture->failed()) {
                    continue;
                }

                $filename = sprintf('recipes/%s/%s', $recipe->id, basename(parse_url($url, PHP_URL_PATH) ?: $url));
                $stored = Storage::disk(config('app.assets.disk'))->put($filename, $picture->body());

                if ($stored) {
                    $pictures[] = $filename;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        return $pictures;
    }

    private function extractReadableText(string $html): string
    {
        $html = preg_replace('#<(script|style|noscript|svg)\b[^>]*>.*?</\1>#is', ' ', $html) ?? $html;
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES);
        $text = preg_replace('/[ \t]+/', ' ', $text) ?? $text;
        $text = preg_replace('/\n\s*\n+/', "\n", $text) ?? $text;

        return Str::limit(trim($text), 20000, '');
    }
}
