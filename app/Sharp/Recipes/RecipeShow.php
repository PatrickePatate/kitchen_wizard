<?php

namespace App\Sharp\Recipes;

use App\Models\Recipe;
use Code16\Sharp\Show\Fields\SharpShowPictureField;
use Code16\Sharp\Show\Fields\SharpShowTextField;
use Code16\Sharp\Show\Layout\ShowLayout;
use Code16\Sharp\Show\Layout\ShowLayoutColumn;
use Code16\Sharp\Show\Layout\ShowLayoutSection;
use Code16\Sharp\Show\SharpShow;
use Code16\Sharp\Utils\Fields\FieldsContainer;

class RecipeShow extends SharpShow
{
    public function buildShowFields(FieldsContainer $showFields): void
    {
        $showFields
            ->addField(
                SharpShowPictureField::make("pictures")
            )
            ->addField(
                SharpShowTextField::make("times:prep")
                    ->setLabel("Temps de préparation")
            )
            ->addField(
                SharpShowTextField::make("times:cook")
                    ->setLabel("Temps de cuisson")
            )
            ->addField(
                SharpShowTextField::make("times:rest_time")
                    ->setLabel("Temps de repos")
            )
            ->addField(
                SharpShowTextField::make("price")
                    ->setLabel("Prix")
            )
            ->addField(
                SharpShowTextField::make("difficulty")
                    ->setLabel("Difficulté")
            )
            ->addField(
                SharpShowTextField::make("people")
                    ->setLabel("Portions")
            )
            ->addField(
                SharpShowTextField::make("meal_type")
                    ->setLabel("Type de plat")
            )
            ->addField(
                SharpShowTextField::make("ingredients")
                    ->setLabel("Ingrédients")
            )
            ->addField(
                SharpShowTextField::make("utensils")
                    ->setLabel("Ustensiles")
            )
            ->addField(
                SharpShowTextField::make("author")
                    ->setLabel("Auteur")
            )
            ->addField(
                SharpShowTextField::make("author_note")
                    ->setLabel("Notes de l'auteur")
            )
            ->addField(
                SharpShowTextField::make("steps")
            );
    }

    public function buildShowConfig(): void
    {
        $this
            ->configureBreadcrumbCustomLabelAttribute("title")
            ->configurePageTitleAttribute("title");
    }

    public function buildShowLayout(ShowLayout $showLayout): void
    {
        $showLayout->addSection("Recette", function (ShowLayoutSection $section) {
            $section
                ->addColumn(4, function (ShowLayoutColumn $column) {
                    $column
                        ->withSingleField("pictures");
                })
                ->addColumn(8, function ($column) {
                    $column
                        ->withFields("times:prep|4", "times:cook|4", "times:rest_time|4")
                        ->withFields("price|6", "difficulty|6")
                        ->withFields("meal_type|6", "people|6")
                        ->withFields("ingredients|6", "utensils|6");
                });
        })
            ->addSection("Étapes", function (ShowLayoutSection $section) {
                $section
                    ->addColumn(12, function ($column) {
                        $column
                            ->withSingleField("steps");
                    });
            })
            ->addSection("Auteur", function (ShowLayoutSection $section) {
                $section
                    ->addColumn(12, function ($column) {
                        $column
                            ->withSingleField("author")
                            ->withSingleField("author_note");
                    });
            });
    }

    protected function find(mixed $id): array
    {
        return $this
            ->setCustomTransformer('pictures', function ($value, Recipe $recipe) {
                return isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : asset('images/default_recipe_picture.webp');
            })
            ->setCustomTransformer('ingredients', function ($value, Recipe $recipe) {
                return "<ul>".collect($recipe->ingredients)->map(fn($ingredient) => sprintf(
                        '<li><b>%s</b> <small>%s</small></li>',
                        $ingredient['label'],
                        $ingredient['quantity_text'] ? "({$ingredient['quantity_text']})" : ""
                    ))->implode('')."</ul>";
            })
            ->setCustomTransformer('utensils', function ($value, Recipe $recipe) {
                return "<ul>".collect($recipe->utensils)->map(fn($utensil) => sprintf(
                        '<li>%s</li>',
                        $utensil['label'],
                    ))->implode('')."</ul>";
            })
            ->setCustomTransformer('times:rest_time', function ($value, Recipe $recipe) {
                return $recipe->times['rest_time'];
            })
            ->setCustomTransformer('times:cook', function ($value, Recipe $recipe) {
                return $recipe->times['cook'];
            })
            ->setCustomTransformer('times:prep', function ($value, Recipe $recipe) {
                return $recipe->times['prep'];
            })
            ->setCustomTransformer('steps', function ($value, Recipe $recipe) {
                return collect($recipe->steps)->map(fn($step) => sprintf(
                    '<div style="margin-bottom: 2rem;"><div>%s</div><div>%s</div></div>',
                    "<b>".$step['heading']."</b>",
                    $step['text']
                ))->implode('');
            })
            ->transform(Recipe::findOrFail($id));
    }

    public function delete(mixed $id): void
    {
        Recipe::findOrFail($id)->delete();
    }
}
