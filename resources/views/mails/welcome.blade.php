<x-mail::message>
# {{__('Bienvenue sur :appname !', ['appname' => config('app.name')])}}

{{__('Vous vous êtes récemment inscrit sur notre plateforme.')}}

{{__('Pour profiter au maximum de :appname, il vous reste à activer un cannal de notification afin de recevoir votre séléction quotidienne de recettes.', ['appname' => config('app.name')])}}

<x-mail::button :url="$url">
{{__('Activer un canal de notification')}}
</x-mail::button>

{{__('À bientôt')}},<br>
{{ config('app.name') }}
</x-mail::message>
