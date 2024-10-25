<?php

namespace App;

enum UserGroupEnum: string
{
    case USER = 'user';
    case ADMIN = 'admin';

    public function label()
    {
        return match ($this) {
            self::USER => 'Utilisateur(trice)',
            self::ADMIN => 'Administrateur(trice)',
        };
    }
}
