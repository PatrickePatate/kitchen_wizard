<?php

namespace App\Sharp\Utils;

use App\UserGroupEnum;
use Code16\Sharp\Auth\SharpAuthenticationCheckHandler;
use Illuminate\Contracts\Auth\Authenticatable;

class SharpAuthCheckHandler implements SharpAuthenticationCheckHandler
{

    public function check(Authenticatable $user): bool
    {
        return $user->group == UserGroupEnum::ADMIN;
    }
}
