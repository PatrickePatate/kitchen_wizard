@extends('layouts.app')
@section('content')
    <div class="container mx-auto min-h-dvh">
        <div class="py-6 px-6 border-x border-b">
            <h1 class="font-heading text-xl">{!! __('Ma liste de <b>courses</b>') !!}</h1>

            <div class="pt-6 pb-4">
                @if($hasRecipes)
                    <livewire:shopping-list />
                @else
                    <x-alert type="info">
                        <p>{{__("Votre liste de courses est vide.")}}</p>
                        <small>{{__('Ajoutez des recettes à votre liste en cliquant sur le panier en haut à droite des recettes.')}}</small>
                    </x-alert>
                @endif
            </div>
        </div>
    </div>
@endsection
