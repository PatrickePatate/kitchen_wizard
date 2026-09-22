@use(Carbon\Carbon)
@use(App\DietEnum)
@use(App\MealTypeEnum)
@extends('layouts.app')
@section('content')
    <div class="container mx-auto min-h-dvh">
        <div class="flex flex-wrap border-x items-center gap-2 mx-auto px-6 pt-6">
            <a href="{{route('search', array_filter(['query' => request('query'), 'meal_type' => $mealType]))}}"
               class="px-3 py-1 rounded-full text-sm {{ !$diet ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                {{__('Tous')}}
            </a>
            @foreach(DietEnum::cases() as $case)
                <a href="{{route('search', array_filter(['query' => request('query'), 'diet' => $case->value, 'meal_type' => $mealType]))}}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm {{ $diet === $case->value ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                    @svg($case->getIcon(), ['class' => 'w-4 h-4'])
                    {{ $case->getLabel() }}
                </a>
            @endforeach
        </div>
        <div class="flex flex-wrap border-x items-center gap-2 mx-auto px-6 pt-2">
            <a href="{{route('search', array_filter(['query' => request('query'), 'diet' => $diet]))}}"
               class="px-3 py-1 rounded-full text-sm {{ !$mealType ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                {{__('Tous types')}}
            </a>
            @foreach(MealTypeEnum::cases() as $case)
                <a href="{{route('search', array_filter(['query' => request('query'), 'diet' => $diet, 'meal_type' => $case->value]))}}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm {{ $mealType === $case->value ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                    @svg($case->getIcon(), ['class' => 'w-4 h-4'])
                    {{ ucfirst($case->value) }}
                </a>
            @endforeach
        </div>
        <!-- Feed header -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mx-auto border-x px-6 pt-6 pb-4 border-b">
            @foreach($recipes as $recipe)
                <x-recipe-card :allow-refresh="false" :recipe="$recipe" />
            @endforeach
            @if($recipes?->isEmpty())
                <div class="w-full text-center md:col-span-2">
                    Aucun résultat
                </div>
            @endif
        </div>
        <div class="my-5 px-3">
            {{ $recipes->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
