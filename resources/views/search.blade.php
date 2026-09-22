@use(Carbon\Carbon)
@use(App\DietEnum)
@use(App\MealTypeEnum)
@use(App\MealDifficultyEnum)
@use(App\RecipeDurationEnum)
@extends('layouts.app')
@section('content')
    @php
        $hasAdvancedFilters = $difficulty || $duration;
        $hasAnyFilter = $diet || $mealType || $difficulty || $duration;
        $baseParams = ['query' => request('query')];
    @endphp
    <div class="container mx-auto min-h-dvh">
        <div x-data="{filtersOpen: {{ $hasAdvancedFilters ? 'true' : 'false' }}}" class="border-x px-6 pt-6 pb-2">
            @if(request('query'))
                <div class="flex items-center gap-2 pb-3 text-sm text-gray-600">
                    <span>{{__('Résultats pour')}}</span>
                    <span class="font-semibold text-neutral-900">« {{ request('query') }} »</span>
                    <a href="{{route('search', array_filter(array_merge($baseParams, ['query' => null, 'diet' => $diet, 'meal_type' => $mealType, 'difficulty' => $difficulty, 'duration' => $duration])))}}"
                       class="inline-flex items-center text-gray-400 hover:text-gray-700" title="{{__('Effacer la recherche')}}">
                        @svg('tabler-x', ['class' => 'w-4 h-4'])
                    </a>
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 w-full sm:w-auto">{{__('Régime')}}</span>
                <a href="{{route('search', array_filter(array_merge($baseParams, ['meal_type' => $mealType, 'difficulty' => $difficulty, 'duration' => $duration])))}}"
                   class="px-3 py-1 rounded-full text-sm {{ !$diet ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                    {{__('Tous')}}
                </a>
                @foreach(DietEnum::cases() as $case)
                    <a href="{{route('search', array_filter(array_merge($baseParams, ['diet' => $case->value, 'meal_type' => $mealType, 'difficulty' => $difficulty, 'duration' => $duration])))}}"
                       class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm {{ $diet === $case->value ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                        @svg($case->getIcon(), ['class' => 'w-4 h-4'])
                        {{ $case->getLabel() }}
                    </a>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-2 pt-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500 w-full sm:w-auto">{{__('Type de plat')}}</span>
                <a href="{{route('search', array_filter(array_merge($baseParams, ['diet' => $diet, 'difficulty' => $difficulty, 'duration' => $duration])))}}"
                   class="px-3 py-1 rounded-full text-sm {{ !$mealType ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                    {{__('Tous types')}}
                </a>
                @foreach(MealTypeEnum::cases() as $case)
                    <a href="{{route('search', array_filter(array_merge($baseParams, ['diet' => $diet, 'meal_type' => $case->value, 'difficulty' => $difficulty, 'duration' => $duration])))}}"
                       class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm {{ $mealType === $case->value ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                        @svg($case->getIcon(), ['class' => 'w-4 h-4'])
                        {{ ucfirst($case->value) }}
                    </a>
                @endforeach
            </div>

            <div class="flex items-center justify-between pt-3">
                <button type="button" @click="filtersOpen = !filtersOpen"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium {{ $hasAdvancedFilters ? 'bg-neutral-900 text-white' : 'bg-gray-100 text-neutral-900' }}">
                    @svg('tabler-adjustments-horizontal', ['class' => 'w-4 h-4'])
                    {{__('Plus de filtres')}}
                    @if($hasAdvancedFilters)
                        <span class="inline-flex items-center justify-center w-4 h-4 text-[10px] rounded-full bg-white text-neutral-900">
                            {{ ($difficulty ? 1 : 0) + ($duration ? 1 : 0) }}
                        </span>
                    @endif
                    @svg('tabler-chevron-down', ['class' => 'w-4 h-4 transition-transform', 'x-bind:class' => "filtersOpen ? 'rotate-180' : ''"])
                </button>

                @if($hasAnyFilter)
                    <a href="{{route('search', array_filter($baseParams))}}" class="text-sm text-gray-500 underline hover:text-gray-800">
                        {{__('Réinitialiser les filtres')}}
                    </a>
                @endif
            </div>

            <div x-cloak x-show="filtersOpen" x-transition class="pt-3 space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-gray-500 w-full sm:w-auto">
                        @svg('tabler-chef-hat', ['class' => 'w-4 h-4'])
                        {{__('Difficulté')}}
                    </span>
                    <a href="{{route('search', array_filter(array_merge($baseParams, ['diet' => $diet, 'meal_type' => $mealType, 'duration' => $duration])))}}"
                       class="px-3 py-1 rounded-full text-sm {{ !$difficulty ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                        {{__('Toutes')}}
                    </a>
                    @foreach(MealDifficultyEnum::cases() as $case)
                        <a href="{{route('search', array_filter(array_merge($baseParams, ['diet' => $diet, 'meal_type' => $mealType, 'difficulty' => $case->value, 'duration' => $duration])))}}"
                           class="px-3 py-1 rounded-full text-sm {{ $difficulty === $case->value ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                            {{ $case->getLabel() }}
                        </a>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wide text-gray-500 w-full sm:w-auto">
                        @svg('tabler-clock', ['class' => 'w-4 h-4'])
                        {{__('Durée totale')}}
                    </span>
                    <a href="{{route('search', array_filter(array_merge($baseParams, ['diet' => $diet, 'meal_type' => $mealType, 'difficulty' => $difficulty])))}}"
                       class="px-3 py-1 rounded-full text-sm {{ !$duration ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                        {{__('Toutes')}}
                    </a>
                    @foreach(RecipeDurationEnum::cases() as $case)
                        <a href="{{route('search', array_filter(array_merge($baseParams, ['diet' => $diet, 'meal_type' => $mealType, 'difficulty' => $difficulty, 'duration' => $case->value])))}}"
                           class="px-3 py-1 rounded-full text-sm {{ $duration === $case->value ? 'bg-neutral-900 text-white' : 'bg-gray-200' }}">
                            {{ $case->getLabel() }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Feed header -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mx-auto border-x px-6 pt-6 pb-4 border-b">
            @foreach($recipes as $recipe)
                <x-recipe-card :allow-refresh="false" :recipe="$recipe" />
            @endforeach
            @if($recipes?->isEmpty())
                <div class="w-full text-center md:col-span-2">
                    {{__('Aucun résultat')}}
                    @if($hasAnyFilter)
                        <div class="pt-2">
                            <a href="{{route('search', array_filter($baseParams))}}" class="text-sm text-gray-500 underline hover:text-gray-800">
                                {{__('Réinitialiser les filtres')}}
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        <div class="my-5 px-3">
            {{ $recipes->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
