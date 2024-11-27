@use(Carbon\Carbon)
@extends('layouts.app')
@section('content')
    <div class="container mx-auto min-h-[100dvh] ">
        <div class="my-6 px-6 border-x border-b">
            <h1 class="font-heading text-xl">{!! __('Mes recettes <b>favorites</b>') !!}</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mx-auto pt-6 pb-4">
                @foreach($recipes->pluck('recipe') as $recipe)
                    <x-recipe-card :recipe="$recipe" />
                @endforeach
            </div>

        </div>
        <div class="my-5 px-3">
            {{ $recipes->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
