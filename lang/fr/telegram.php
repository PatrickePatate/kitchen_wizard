<?php

return [
    'start' => [
        '👋 Bienvenue sur :app_name!',
        '',
        '🔗 Pour lier votre compte, tapez /link `<VOTRE EMAIL>`',
        '',
        '📝 Exemple: /link'
    ],
    'link' => [
        '🚀 Hey ! Bonne nouvelle !',
        '',
        "C'est la dernière ligne droite ! 🏁",
        '',
        'Vérifie tes mails, tu as reçu un code de validation pour valider la laison de ton compte !'
    ],
    'code' => [
        '🎉 Félicitations ! 🎉',
        '',
        'Votre compte a été lié à :app_name ! 🚀',
        '',
        'Vous recevrez désormais des suggestions de recettes les matins via Telegram !'
    ],
    'errors' => [
        'link' => [
            'no_email' => [
                '⚠️ Oups ! Une erreur s\'est produite !',
                '',
                '🔍 L\'email est obligatoire pour lier votre compte !',
                '',
                '📝 Exemple: /link arthur@example.org',
            ],
            'no_user' => [
                '⚠️ Oups ! Une erreur s\'est produite !',
                '',
                '🔍 Aucun compte n\'a été trouvé avec l\'email :email',
            ]
        ],
        'code' => [
            'no_code' => [
                '⚠️ Oups ! Une erreur s\'est produite !',
                '',
                '🔍 Le code est obligatoire pour lier votre compte !',
                '',
                '📝 Exemple: /code 123456',
            ],
            'no_user' => [
                '⚠️ Oups ! Une erreur s\'est produite !',
                '',
                '🔍 Le code de validation est incorrect ou a expiré !',
            ]
        ]
    ]
];
