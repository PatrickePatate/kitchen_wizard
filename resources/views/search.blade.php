@use(Carbon\Carbon)
@extends('layouts.app')
@section('content')
    <div class="container mx-auto min-h-[100dvh]">
        <!-- Feed header -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mx-auto border-x px-6 pt-6 pb-4 border-b">
            @foreach($recipes as $recipe)
                <x-recipe-card :allow-refresh="false" :recipe="$recipe" />
            @endforeach
        </div>
        <div class="my-5 px-3">
            {{ $recipes->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
