<?php

namespace App\Sharp\Users;

use App\Models\User;
use App\Sharp\Users\Commands\UserDemoteAdminCommand;
use App\Sharp\Users\Commands\UserPromoteAdminCommand;
use App\UserGroupEnum;
use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\Fields\EntityListFieldsLayout;
use Code16\Sharp\EntityList\SharpEntityList;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;

class UsersList extends SharpEntityList
{
    protected function buildList(EntityListFieldsContainer $fields): void
    {
        $fields
            ->addField(
                EntityListField::make('avatar')
                    ->setWidth(1)
            )
            ->addField(
                EntityListField::make('name')
                    ->setLabel(__('Name'))
            )
            ->addField(
                EntityListField::make('email')
                    ->setLabel(__('Email'))
            )
            ->addField(
                EntityListField::make('created_at')
                    ->setLabel(__('Created at'))
            )
            ->addField(
                EntityListField::make('group')
                    ->setLabel(__('Group'))
                    ->setWidth(2)
            );
    }

    public function buildListConfig(): void
    {
        $this->configureSearchable();
    }

    protected function getInstanceCommands(): ?array
    {
        return [
            UserPromoteAdminCommand::class,
            UserDemoteAdminCommand::class
        ];
    }

    protected function getEntityCommands(): ?array
    {
        return [];
    }

    protected function getFilters(): array
    {
        return [];
    }

    public function getListData(): array|Arrayable
    {
        return $this
            ->setCustomTransformer('group', function($value, User $user) {
                return match(true) {
                    $user->group === UserGroupEnum::USER => sprintf('<span style="color: cornflowerblue;">%s</span>', $user->group->label()),
                    $user->group === UserGroupEnum::ADMIN => sprintf('<span style="color: orangered;">%s</span>', $user->group->label()),
                };
            })
            ->setCustomTransformer('avatar', function($value, User $user) {
                return sprintf('<img style="max-height: 55px;" src="%s" alt="avatar"/>', $user->avatar);
            })
            ->setCustomTransformer('created_at', function($value, User $user) {
                return $user->created_at->translatedFormat('d F Y H:i');
            })
            ->transform(
                User::query()
                    ->orderBy('created_at', 'desc')
                    ->when($this->queryParams->hasSearch(), function(Builder $query) {
                        foreach ($this->queryParams->searchWords() as $word) {
                            $query->where('name', 'like' ,$word)
                                ->orWhere('email', 'like', $word);
                        }
                    })
                    ->when($this->queryParams->specificIds(), fn(Builder $query, array $ids) => $query->whereIn('id', $ids))
                    ->get()
            );
    }

    public function delete(mixed $id): void
    {
        $user = User::findOrFail($id);
        $user->dailySelections()->delete();
        $user->delete();
    }
}
