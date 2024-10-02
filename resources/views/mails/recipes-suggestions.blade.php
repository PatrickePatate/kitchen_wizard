<x-mail::message>
# Vos suggestions de recettes pour ce {{Carbon\Carbon::today()->translatedFormat('l d F')}}

Bonjour {{$user->name}},
Voici quelques recettes qui pourraient vous intéresser !

<x-mail::panel>
## Entrée
![Image de la recette]({{ asset('storage/'.$selection->starter()?->pictures[0])??asset('images/default_recipe_picture.webp') }})

[{{$selection->starter()?->title}}]({{$selection->starter()?->url}})
</x-mail::panel>

<x-mail::panel>
## Plat
![Image de la recette]({{ asset('storage/'.$selection->main()?->pictures[0])??asset('images/default_recipe_picture.webp') }})

[{{$selection->main()?->title}}]({{$selection->main()?->url}})
</x-mail::panel>

<x-mail::panel>
## Dessert
![Image de la recette]({{ asset('storage/'.$selection->dessert()?->pictures[0])??asset('images/default_recipe_picture.webp') }})

[{{$selection->dessert()?->title}}]({{$selection->dessert()?->url}})
</x-mail::panel>

<x-mail::button :url="route('home')">
Voir les recettes
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
