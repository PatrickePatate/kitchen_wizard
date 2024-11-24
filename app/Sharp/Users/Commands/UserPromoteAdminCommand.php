<?php

namespace App\Sharp\Users\Commands;

use App\Models\User;
use App\UserGroupEnum;
use Code16\Sharp\EntityList\Commands\InstanceCommand;

class UserPromoteAdminCommand extends InstanceCommand
{
    public function label(): string
    {
        return __("Promouvoir en administrateur");
    }

    public function buildCommandConfig(): void
    {
        $this->configureDescription(__("L'utilisateur(trice) aura accès à l'administration."));
    }

    public function execute($instanceId, array $data = []): array
    {
        User::findOrFail($instanceId)->update([
            'group' => UserGroupEnum::ADMIN
        ]);

        return $this->refresh($instanceId);
    }

    public function authorizeFor($instanceId): bool
    {
        return User::findOrFail($instanceId)->group !== UserGroupEnum::ADMIN;
    }
}
