<x-mail::message>
# {{__('Validez votre compte Telegram')}}

{{__('Bonjour :name', ['name' => $user->name])}},

{{__("Merci de valider votre compte Telegram en entrant le code suivant dans l'application Telegram avec la commande suivante :")}}
<pre>/code {{$code}}</pre>

<x-mail::panel>{{$code}}</x-mail::panel>

<x-mail::button :url="urlencode('tg://msg?text=/code '.$code)">
{{__('Valider mon compte Telegram')}}
</x-mail::button>

{{__('Merci')}},<br>
{{ config('app.name') }}
</x-mail::message>
