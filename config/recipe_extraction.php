<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Recipe URL extraction provider/model
    |--------------------------------------------------------------------------
    |
    | Which AI provider (see config/ai.php "providers") and model to use when
    | parsing a recipe from a URL in the Sharp "Créer depuis une URL" command.
    | Left null, the provider falls back to laravel/ai's own default (config
    | ai.default) and the model to that provider's default model.
    |
    */

    'provider' => env('RECIPE_EXTRACTION_PROVIDER', 'anthropic'),

    'model' => env('RECIPE_EXTRACTION_MODEL', 'claude-sonnet-5'),

    /*
    |--------------------------------------------------------------------------
    | Reasoning effort
    |--------------------------------------------------------------------------
    |
    | One of "low", "medium" or "high". Mapped to the resolved provider's own
    | reasoning/thinking option (see App\Ai\RecipeUrlExtractorAgent::providerOptions()).
    | Leave null to use the model's default behaviour (no extended reasoning).
    |
    */

    'reasoning_effort' => env('RECIPE_EXTRACTION_REASONING_EFFORT'),

];
