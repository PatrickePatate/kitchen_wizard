<?php

namespace App\Ai;

use App\DietEnum;
use App\MealDifficultyEnum;
use App\MealTypeEnum;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasProviderOptions;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;

/**
 * Reads a recipe web page (fetched server-side and passed in as plain text)
 * and extracts it into the shape expected by App\Models\Recipe.
 *
 * Sonnet 5 is used here rather than Haiku: recipe pages are messy (ads,
 * comments, unrelated "you might also like" sections) and the model has to
 * make judgment calls -- inferring difficulty/diet/seasonality when the page
 * doesn't state them, splitting free-form instructions into discrete steps,
 * normalizing quantities -- which benefits from a stronger model. Swap the
 * #[Model] attribute to 'claude-haiku-4-5-20251001' if cost matters more than
 * accuracy on well-structured recipe sites (e.g. sites using schema.org
 * Recipe markup, where extraction is closer to a straight parse).
 *
 * The provider/model/reasoning effort are read from config/recipe_extraction.php
 * (itself driven by the RECIPE_EXTRACTION_PROVIDER / RECIPE_EXTRACTION_MODEL /
 * RECIPE_EXTRACTION_REASONING_EFFORT env vars) instead of the usual
 * #[Provider]/#[Model] attributes, so they can be changed per environment
 * without a code change.
 */
class RecipeUrlExtractorAgent implements Agent, HasStructuredOutput, HasProviderOptions
{
    use Promptable;

    public function provider(): ?string
    {
        return config('recipe_extraction.provider');
    }

    public function model(): ?string
    {
        return config('recipe_extraction.model');
    }

    /**
     * Maps the configured reasoning effort (low/medium/high) to whatever
     * shape the resolved provider expects. Returns an empty array (no-op)
     * for providers/models that don't support a reasoning effort, and when
     * RECIPE_EXTRACTION_REASONING_EFFORT is left empty.
     */
    public function providerOptions(Lab|string $provider): array
    {
        $effort = config('recipe_extraction.reasoning_effort');

        if (! $effort) {
            return [];
        }

        $driver = $provider instanceof Lab ? $provider->value : $provider;

        return match ($driver) {
            'anthropic' => [
                'thinking' => [
                    'type' => 'enabled',
                    'budget_tokens' => match ($effort) {
                        'low' => 2_000,
                        'high' => 24_000,
                        default => 8_000,
                    },
                ],
            ],
            'openai', 'azure', 'openai-compatible' => [
                'reasoning' => ['effort' => $effort],
            ],
            'xai', 'groq', 'deepseek' => [
                'reasoning_effort' => $effort,
            ],
            default => [],
        };
    }

    public function instructions(): string
    {
        return <<<'TEXT'
            You extract structured recipe data from the text content of a recipe web page.

            Rules:
            - Only use information present in the page content. Never invent ingredients, steps or quantities.
            - Write all output text in French, translating if the source page is in another language.
            - "steps" must break the instructions into individual, self-contained steps, each with a short heading and the full instruction text.
            - "ingredients" quantity_text should be a short human-readable quantity (e.g. "200 g", "2 cuillères à soupe"), or null if not specified.
            - "total_time", "prep_time", "cook_time" and "rest_time" should be short human-readable durations (e.g. "20 min", "1 h 30"), or null if not found.
            - "difficulty" and "meal_type" must be inferred as best you can from the content when not explicit.
            - "pictures" must only contain absolute image URLs found in the page content (e.g. og:image or inline image URLs). Return an empty array if none are found.
            - If the page clearly isn't a recipe, still fill in whatever fields you can from the content, leaving the rest null/empty.
            TEXT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->required(),
            'meal_type' => $schema->string()
                ->enum(MealTypeEnum::class)
                ->nullable(),
            'difficulty' => $schema->string()
                ->enum(MealDifficultyEnum::class)
                ->nullable(),
            'diet' => $schema->string()
                ->enum(DietEnum::class)
                ->nullable(),
            'seasonality' => $schema->string()
                ->enum(['spring', 'summer', 'autumn', 'winter', 'unknown'])
                ->nullable(),
            'total_time' => $schema->string()->nullable(),
            'prep_time' => $schema->string()->nullable(),
            'cook_time' => $schema->string()->nullable(),
            'rest_time' => $schema->string()->nullable(),
            'price' => $schema->string()->nullable(),
            'people' => $schema->integer()->min(1)->nullable(),
            'author' => $schema->string()->nullable(),
            'author_note' => $schema->string()->nullable(),
            'pictures' => $schema->array()->items($schema->string())->required(),
            'ingredients' => $schema->array()
                ->items(
                    $schema->object([
                        'label' => $schema->string()->required(),
                        'quantity_text' => $schema->string()->nullable(),
                    ])
                )
                ->required(),
            'utensils' => $schema->array()
                ->items(
                    $schema->object([
                        'label' => $schema->string()->required(),
                    ])
                )
                ->required(),
            'steps' => $schema->array()
                ->items(
                    $schema->object([
                        'heading' => $schema->string()->nullable(),
                        'text' => $schema->string()->required(),
                    ])
                )
                ->required(),
        ];
    }
}
