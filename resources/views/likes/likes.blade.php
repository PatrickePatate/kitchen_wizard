@use(Carbon\Carbon)
@extends('layouts.app')
@section('content')
    <div class="container mx-auto min-h-[100dvh] ">
        <div class="py-6 px-6 border-x border-b">
            <h1 class="font-heading text-xl">{!! __('Mes recettes <b>favorites</b>') !!}</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mx-auto pt-6 pb-4">
                @forelse($recipes->pluck('recipe') as $recipe)
                    <x-recipe-card :recipe="$recipe" />
                @empty
                    <div class="md:col-span-2">
                        <div class="bg-gray-200 text-dark p-4 rounded-xl flex items-center gap-4">
                            <div>
                                <x-tabler-alert-triangle></x-tabler-alert-triangle>
                            </div>
                            <div>
                                <p>{{__("Vous n'avez pas encore de recettes favorites.")}}</p>
                                <small>{{__('Ajoutez des recettes en favoris en cliquant sur le coeur en haut à droite des recettes.')}}</small>
                            </div>
                    </div>
                @endforelse
            </div>

        </div>
        <div class="my-5 px-3">
            {{ $recipes->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
