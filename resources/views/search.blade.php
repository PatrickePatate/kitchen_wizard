@extends('layouts.app')
@section('title', __('Search').' - '.config('app.name'))
@section('content')
    <div class="container mx-auto min-h-dvh">
        <div class="mx-auto border-x px-6 pt-6 pb-4 border-b">
            <h1 class="font-heading text-xl mb-1">{{ __('Search') }}@if(request('query')) : <span class="font-semibold">{{ request('query') }}</span>@endif</h1>
            <p class="text-sm text-gray-600 mb-6">{{ trans_choice('{0} No results|{1} :count result|[2,*] :count results', $recipes->total()) }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                @forelse($recipes as $recipe)
                    <x-recipe-card :allow-refresh="false" :recipe="$recipe" />
                @empty
                    <x-alert type="info">
                        <p>{{ __('No recipe matches your search.') }}</p>
                        <a href="{{ route('home') }}" class="font-semibold underline">{{ __('Back to today\'s suggestions') }}</a>
                    </x-alert>
                @endforelse
            </div>
        </div>
        <div class="my-5 px-3">
            {{ $recipes->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
