<?php

namespace App\Sharp\Entities;

use App\Sharp\Users\UsersForm;
use App\Sharp\Users\UsersList;
use Code16\Sharp\Utils\Entities\SharpEntity;

class UserEntity extends SharpEntity
{
    public string $label = "Utilisateur";
    protected ?string $list = UsersList::class;
    protected ?string $form = UsersForm::class;

    protected array $prohibitedActions = ["create"];
}
