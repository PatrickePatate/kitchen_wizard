<x-mail::message>
# {{__('Vos suggestions de recettes pour ce :date', ['date' => Carbon\Carbon::today()->translatedFormat('l d F')])}}

{{__('Bonjour :name', ['name' => $user->name])}},
{{__('Voici quelques recettes qui pourraient vous intéresser !')}}

<x-mail::panel>
## {{__('Entrée')}}
![Image de la recette]({{ asset('storage/'.$selection->starter()?->pictures[0])??asset('images/default_recipe_picture.webp') }})

[{{$selection->starter()?->title}}]({{$selection->starter()?->url}})
</x-mail::panel>

<x-mail::panel>
## {{__('Plat principal')}}
![Image de la recette]({{ asset('storage/'.$selection->main()?->pictures[0])??asset('images/default_recipe_picture.webp') }})

[{{$selection->main()?->title}}]({{$selection->main()?->url}})
</x-mail::panel>

<x-mail::panel>
## {{__('Dessert')}}
![Image de la recette]({{ asset('storage/'.$selection->dessert()?->pictures[0])??asset('images/default_recipe_picture.webp') }})

[{{$selection->dessert()?->title}}]({{$selection->dessert()?->url}})
</x-mail::panel>

<x-mail::button :url="route('home')">
{{__('Voir les recettes')}}
</x-mail::button>

{{__('Merci')}},<br>
{{ config('app.name') }}
</x-mail::message>
