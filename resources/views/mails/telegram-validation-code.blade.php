<x-mail::message>
# Validez votre compte Telegram

Bonjour {{$user->name}},
Merci de valider votre compte Telegram en entrant le code suivant dans l'application Telegram avec la commande <pre>/code {{$code}}</pre>

<x-mail::panel># {{$code}}</x-mail::panel>

<x-mail::button :url="urlencode('tg://msg?text=/code '.$code)">
Valider mon compte Telegram
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
