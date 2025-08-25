<?php

namespace App\Sharp\Users;

use App\Models\User;
use App\UserGroupEnum;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Layout\FormLayout;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Utils\Fields\FieldsContainer;
use Illuminate\Validation\Rule;

class UsersForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;

    public function buildFormFields(FieldsContainer $formFields): void
    {
        $formFields
            ->addField(
                SharpFormTextField::make('name')
                    ->setLabel(__('Fullname'))
            )
            ->addField(
                SharpFormTextField::make('email')
                    ->setLabel(__('Email'))
            )
            ->addField(
                SharpFormSelectField::make('group', collect(UserGroupEnum::cases())->mapWithKeys(fn(UserGroupEnum $group) => [$group->value => $group->label()])->all())
                ->setLabel(__('Group'))
            );
    }

    public function buildFormLayout(FormLayout $formLayout): void
    {
         $formLayout
             ->addColumn(6, function (FormLayoutColumn $column) {
                 $column
                     ->withFields('name', 'email')
                     ->withField('group');
             });
    }

    public function find($id): array
    {
        return $this->transform(User::findOrFail($id));
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);

        $this->save($user, $data);

        return $user->id;
    }

    public function rules(): array
    {
    	return [
    		'name' => 'required|string|max:250',
            'email' => [
                'required',
                'email:rfc',
                Rule::unique('users', 'email')->ignore(sharp()->context()->instanceId()),
            ],
            'group' => Rule::in(UserGroupEnum::cases())
    	];
    }
}
