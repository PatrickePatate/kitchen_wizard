<?php

namespace App\Sharp\Recipes;

use App\DietEnum;
use App\MealDifficultyEnum;
use App\MealTypeEnum;
use App\Models\Recipe;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormListField;
use Code16\Sharp\Form\Fields\SharpFormNumberField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Fields\SharpFormTextareaField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Layout\FormLayout;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Utils\Fields\FieldsContainer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RecipeForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;

    public function buildFormFields(FieldsContainer $formFields): void
    {
        $formFields
            ->addField(
                SharpFormTextField::make('title')
                    ->setLabel(__('Titre'))
            )
            ->addField(
                SharpFormTextField::make('url')
                    ->setLabel(__('URL source'))
            )
            ->addField(
                SharpFormSelectField::make('meal_type', collect(MealTypeEnum::cases())
                    ->mapWithKeys(fn (MealTypeEnum $case) => [$case->value => Str::ucfirst($case->value)])
                    ->all())
                    ->setLabel(__('Type de plat'))
            )
            ->addField(
                SharpFormSelectField::make('difficulty', collect(MealDifficultyEnum::cases())
                    ->mapWithKeys(fn (MealDifficultyEnum $case) => [$case->value => $case->getLabel()])
                    ->all())
                    ->setLabel(__('Difficulté'))
            )
            ->addField(
                SharpFormSelectField::make('diet', collect(DietEnum::cases())
                    ->mapWithKeys(fn (DietEnum $case) => [$case->value => $case->getLabel()])
                    ->all())
                    ->setClearable()
                    ->setLabel(__('Régime'))
            )
            ->addField(
                SharpFormSelectField::make('seasonality', [
                    'spring' => __('Printemps'),
                    'summer' => __('Été'),
                    'autumn' => __('Automne'),
                    'winter' => __('Hiver'),
                    'unknown' => __('Inconnue'),
                ])
                    ->setClearable()
                    ->setLabel(__('Saisonnalité'))
            )
            ->addField(
                SharpFormTextField::make('total_time')
                    ->setLabel(__('Temps total'))
            )
            ->addField(
                SharpFormTextField::make('times_prep')
                    ->setLabel(__('Temps de préparation'))
            )
            ->addField(
                SharpFormTextField::make('times_cook')
                    ->setLabel(__('Temps de cuisson'))
            )
            ->addField(
                SharpFormTextField::make('times_rest_time')
                    ->setLabel(__('Temps de repos'))
            )
            ->addField(
                SharpFormTextField::make('price')
                    ->setLabel(__('Prix'))
            )
            ->addField(
                SharpFormNumberField::make('people')
                    ->setLabel(__('Portions'))
                    ->setMin(1)
                    ->setStep(1)
            )
            ->addField(
                SharpFormTextField::make('author')
                    ->setLabel(__('Auteur'))
            )
            ->addField(
                SharpFormTextareaField::make('author_note')
                    ->setLabel(__("Notes de l'auteur"))
                    ->setRowCount(3)
            )
            ->addField(
                SharpFormListField::make('pictures')
                    ->setLabel(__('Photos'))
                    ->setAddable()
                    ->setRemovable()
                    ->setSortable()
                    ->setAddText(__('Ajouter une photo'))
                    ->addItemField(
                        SharpFormTextField::make('url')
                            ->setLabel(__('URL ou chemin de stockage'))
                    )
            )
            ->addField(
                SharpFormListField::make('ingredients')
                    ->setLabel(__('Ingrédients'))
                    ->setAddable()
                    ->setRemovable()
                    ->setSortable()
                    ->setAddText(__('Ajouter un ingrédient'))
                    ->addItemField(
                        SharpFormTextField::make('label')
                            ->setLabel(__('Nom'))
                    )
                    ->addItemField(
                        SharpFormTextField::make('quantity_text')
                            ->setLabel(__('Quantité'))
                    )
            )
            ->addField(
                SharpFormListField::make('utensils')
                    ->setLabel(__('Ustensiles'))
                    ->setAddable()
                    ->setRemovable()
                    ->setSortable()
                    ->setAddText(__('Ajouter un ustensile'))
                    ->addItemField(
                        SharpFormTextField::make('label')
                            ->setLabel(__('Nom'))
                    )
            )
            ->addField(
                SharpFormListField::make('steps')
                    ->setLabel(__('Étapes'))
                    ->setAddable()
                    ->setRemovable()
                    ->setSortable()
                    ->setAddText(__('Ajouter une étape'))
                    ->addItemField(
                        SharpFormTextField::make('heading')
                            ->setLabel(__('Titre'))
                    )
                    ->addItemField(
                        SharpFormTextareaField::make('text')
                            ->setLabel(__('Description'))
                            ->setRowCount(3)
                    )
            );
    }

    public function buildFormLayout(FormLayout $formLayout): void
    {
        $formLayout
            ->addTab(__('Général'), function ($tab) {
                $tab->addColumn(8, function (FormLayoutColumn $column) {
                    $column
                        ->withFields('title')
                        ->withFields('meal_type|4', 'difficulty|4', 'diet|4')
                        ->withFields('total_time|4', 'price|4', 'people|4')
                        ->withFields('times_prep|4', 'times_cook|4', 'times_rest_time|4')
                        ->withFields('seasonality|6', 'url|6');
                })
                    ->addColumn(4, function (FormLayoutColumn $column) {
                        $column
                            ->withField('author')
                            ->withField('author_note');
                    });
            })
            ->addTab(__('Photos'), function ($tab) {
                $tab->addColumn(12, function (FormLayoutColumn $column) {
                    $column->withListField('pictures', function (FormLayoutColumn $item) {
                        $item->withField('url');
                    });
                });
            })
            ->addTab(__('Ingrédients & ustensiles'), function ($tab) {
                $tab->addColumn(6, function (FormLayoutColumn $column) {
                    $column->withListField('ingredients', function (FormLayoutColumn $item) {
                        $item->withFields('label|6', 'quantity_text|6');
                    });
                })
                    ->addColumn(6, function (FormLayoutColumn $column) {
                        $column->withListField('utensils', function (FormLayoutColumn $item) {
                            $item->withField('label');
                        });
                    });
            })
            ->addTab(__('Étapes'), function ($tab) {
                $tab->addColumn(12, function (FormLayoutColumn $column) {
                    $column->withListField('steps', function (FormLayoutColumn $item) {
                        $item
                            ->withField('heading')
                            ->withField('text');
                    });
                });
            });
    }

    public function find($id): array
    {
        return $this
            // "url" collides with the Recipe::url() accessor (the recipe's
            // internal route), so we need to read the actual DB column here.
            ->setCustomTransformer('url', fn ($value, Recipe $recipe) => $recipe->getRawOriginal('url'))
            ->setCustomTransformer('times_prep', fn ($value, Recipe $recipe) => $recipe->times['prep'] ?? null)
            ->setCustomTransformer('times_cook', fn ($value, Recipe $recipe) => $recipe->times['cook'] ?? null)
            ->setCustomTransformer('times_rest_time', fn ($value, Recipe $recipe) => $recipe->times['rest_time'] ?? null)
            ->setCustomTransformer('pictures', fn ($value, Recipe $recipe) => $this->withSyntheticIds(
                collect($recipe->pictures)->map(fn ($url) => ['url' => $url])->all()
            ))
            ->setCustomTransformer('ingredients', fn ($value, Recipe $recipe) => $this->withSyntheticIds($recipe->ingredients))
            ->setCustomTransformer('utensils', fn ($value, Recipe $recipe) => $this->withSyntheticIds($recipe->utensils))
            ->setCustomTransformer('steps', fn ($value, Recipe $recipe) => $this->withSyntheticIds($recipe->steps))
            ->transform(Recipe::findOrFail($id));
    }

    public function update($id, array $data)
    {
        $recipe = Recipe::findOrFail($id);

        $data['times'] = [
            'prep' => Arr::pull($data, 'times_prep'),
            'cook' => Arr::pull($data, 'times_cook'),
            'rest_time' => Arr::pull($data, 'times_rest_time'),
        ];

        $data['pictures'] = collect($data['pictures'] ?? [])
            ->map(fn ($item) => Arr::except($item, ['id']))
            ->pluck('url')
            ->all();

        foreach (['ingredients', 'utensils', 'steps'] as $listAttribute) {
            $data[$listAttribute] = $this->mergeListPreservingUnlistedKeys(
                $recipe->{$listAttribute},
                $data[$listAttribute] ?? []
            );
        }

        $this->save($recipe, $data);

        return $recipe->id;
    }

    /**
     * The ingredients/utensils/steps JSON columns can carry extra keys that
     * the form doesn't expose (e.g. ingredients also have "quantity",
     * "quantity_unit" and "singular", used by other services). Merge the
     * edited fields over each original item (matched by its synthetic id)
     * instead of replacing it outright, so those untouched keys survive.
     */
    private function mergeListPreservingUnlistedKeys(array $originalItems, array $submittedItems): array
    {
        return collect($submittedItems)
            ->map(function ($item) use ($originalItems) {
                $id = $item['id'] ?? null;
                $original = is_int($id) && array_key_exists($id, $originalItems) ? $originalItems[$id] : [];

                return array_merge($original, Arr::except($item, ['id']));
            })
            ->values()
            ->all();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:250',
            'url' => 'required|string|max:2000',
            'meal_type' => 'nullable|string',
            'difficulty' => 'nullable|string',
            'diet' => 'nullable|string',
            'seasonality' => 'nullable|string',
            'total_time' => 'nullable|string|max:100',
            'times_prep' => 'nullable|string|max:100',
            'times_cook' => 'nullable|string|max:100',
            'times_rest_time' => 'nullable|string|max:100',
            'price' => 'nullable|string|max:100',
            'people' => 'nullable|integer|min:1',
            'author' => 'nullable|string|max:250',
            'author_note' => 'nullable|string',
            'pictures' => 'array',
            'pictures.*.url' => 'required|string|max:2000',
            'ingredients' => 'array',
            'ingredients.*.label' => 'required|string|max:250',
            'ingredients.*.quantity_text' => 'nullable|string|max:100',
            'utensils' => 'array',
            'utensils.*.label' => 'required|string|max:250',
            'steps' => 'array',
            'steps.*.heading' => 'nullable|string|max:250',
            'steps.*.text' => 'required|string',
        ];
    }

    /**
     * Add a synthetic, index-based "id" to each item of a list attribute, as
     * required by SharpFormListField, since these JSON columns don't carry a
     * persistent identifier per item.
     */
    private function withSyntheticIds(array $items): array
    {
        return collect($items)
            ->values()
            ->map(fn ($item, $index) => array_merge(['id' => $index], $item))
            ->all();
    }
}
