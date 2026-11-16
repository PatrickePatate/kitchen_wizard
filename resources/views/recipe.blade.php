@extends('layouts.app')
@section('meta')
    <meta property="og:title" content="{{$recipe->title}}" />
    <meta property="og:url" content="{{$recipe->url}}" />
    <meta property="og:image" content="{{ $recipe->main_picture_url }}" />
@endsection
@section('title', $recipe->title. ' - '.config('app.name'))
@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto mt-8">
            <a href="{{route('home')}}" class="inline-flex gap-2 items-center mb-7">
                <x-tabler-arrow-left class="w-8 h-auto text-gray-600" aria-hidden="true"></x-tabler-arrow-left>
                <span class="font-light">{{__('Retour')}}</span>
            </a>

            @if(!auth()->check() && isset($share))
                <x-alert class="mb-6">
                    Cette recette a été partagée avec vous par <strong>{{$share->sharer?->name}}</strong>.<br>
                </x-alert>
            @endif

            <div class="flex gap-2 items-center justify-between mb-3">
                <div class="flex items-center gap-4">
                    <div class="bg-gray-200 rounded-full p-2 w-10 h-10 flex justify-center items-center">
                        @svg($recipe->meal_type?->getIcon())
                    </div>
                    <h2 class="font-sans uppercase font-semibold text-lg">{{$recipe->meal_type}}</h2>
                </div>
                @if(auth()->check())
                <div class="flex items-center gap-3">
                    <livewire:actions.add-to-shopping-list :recipe="$recipe" wrapper-class="static" icon-class="h-7 w-7 text-black" />
                    <x-dialog :label="__('Partager la recette')">
                        <x-slot name="button">
                            <x-tabler-share class="text-black h-7"/>
                        </x-slot>
                        <x-slot name="title">
                            Partager la recette
                        </x-slot>
                        <x-share-link :url="route('recipe.api.share', ['recipe' => $recipe])">
                            Partager ce lien avec vos amis pour leur faire découvrir la recette <em>{{$recipe->title}}</em> :
                        </x-share-link>
                    </x-dialog>
                </div>
                @endif
            </div>

            <x-recipe-card class="mb-10" :recipe="$recipe" :carousel="true" :allowRefresh="false" :clickable="false"/>

            <div class="grid grid-cols-3 gap-2 mb-10 text-center">
                @foreach([['Temps de preparation','prep','tabler-tools-kitchen-2'],['Temps de cuisson','cook','tabler-flame'],['Temps de repos','rest_time','tabler-zzz']] as [$timeLabel, $timeKey, $timeIcon])
                    <div class="bg-neutral-100 rounded-xl p-3 flex flex-col items-center gap-1">
                        @svg($timeIcon, ['class' => 'w-6 h-6 text-gray-600', 'aria-hidden' => 'true'])
                        <p class="text-xs sm:text-sm text-gray-600">{{ $timeLabel }}</p>
                        <span class="font-semibold">{{ $recipe->times[$timeKey] ?? '-' }}</span>
                    </div>
                @endforeach
            </div>

            <div x-data="ingredients" class="flex flex-col gap-2 p-4 bg-neutral-100 rounded-xl mb-6">
                <button type="button" class="flex justify-between w-full cursor-pointer" @click="open = !open" :aria-expanded="open">
                    <h3 class="font-sans font-medium uppercase text-md flex items-center gap-1">
                        <x-tabler-apple class="inline" />
                        <span class="pt-1">Ingrédients :</span>
                    </h3>
                    <div>
                        <x-tabler-chevron-up x-show="open" />
                        <x-tabler-chevron-down x-show="!open" />
                    </div>
                </button>
                <div class="w-full mt-3 " x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="mb-3">
                        <div class="flex gap-2 items-center">
                            <span class="text-md font-sans font-thin">Pour</span>
                            <button type="button" class="w-8 h-8 rounded-full bg-white border border-neutral-300 text-lg leading-none" aria-label="{{__('Fewer servings')}}" @click="setServings(servings - 1)">−</button>
                            <input type="number" min="1" x-model.number="servings" aria-label="{{__('Servings')}}" class="w-14 h-8 text-center border border-neutral-300 rounded-md bg-white" @change="setServings(servings)">
                            <button type="button" class="w-8 h-8 rounded-full bg-white border border-neutral-300 text-lg leading-none" aria-label="{{__('More servings')}}" @click="setServings(servings + 1)">+</button>
                            <span class="text-md font-sans font-thin">personne(s)</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                        <template x-for="ing in ingredients" :key="ing.label">
                            <div  class="flex flex-col flex-nowrap gap-1">
                                <h3 class="text-lg font-semibold pt-1 capitalize" x-text="ing.label"></h3>
                                <h4 class="text-sm font-normal pt-1" x-text="ing.quantity == 0 ? '' : (ing.new_quantity || ing.quantity_text)"></h4>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div x-data="{open: false}" class="flex flex-col gap-2 p-4 bg-neutral-100 rounded-xl mb-12">
                <button type="button" class="flex justify-between w-full cursor-pointer" @click="open = !open" :aria-expanded="open">
                    <h3 class="font-sans font-medium uppercase text-md flex items-center gap-1" >
                        <x-tabler-grill-fork class="inline" />
                        <span class="pt-1">Ustensiles :</span>
                    </h3>
                    <div>
                        <x-tabler-chevron-up x-show="open"></x-tabler-chevron-up>
                        <x-tabler-chevron-down x-show="!open" />
                    </div>
                </button>
                <div class="w-full mt-3 grid grid-cols-2 sm:grid-cols-4 gap-5" x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    @foreach($recipe->utensils as $utensil)
                        <div class="flex flex-col flex-nowrap gap-1">
                            <h3 class="text-lg font-semibold pt-1 capitalize ">{{$utensil['label']}}</h3>
                        </div>
                    @endforeach
                </div>
            </div>

            <section id="steps" class="mb-10">
                <h3 class="font-sans font-medium uppercase text-md flex items-center gap-1 mb-3">
                    <x-tabler-cooker class="inline" />
                    <span class="pt-1">Préparation :</span>
                </h3>
                <ol class="grid grid-cols-1 gap-4">
                    @foreach($recipe->steps as $step)
                        <li x-data="{done: false}" class="step relative p-3 rounded-xl flex gap-3 items-start" :class="done ? 'bg-green-50' : ''" id="step-{{$loop->iteration}}">
                            <button type="button" @click="done = !done" :aria-pressed="done" aria-label="{{ __('Mark step :n as done', ['n' => $loop->iteration]) }}"
                                    class="shrink-0 w-8 h-8 rounded-full border-2 flex items-center justify-center font-semibold text-sm"
                                    :class="done ? 'bg-green-600 border-green-600 text-white' : 'border-neutral-300 text-neutral-700'">
                                <span x-show="!done">{{$loop->iteration}}</span>
                                <x-tabler-check x-show="done" x-cloak class="w-4 h-4" />
                            </button>
                            <div class="grow flex flex-col gap-1" :class="done ? 'opacity-60' : ''">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-sans font-medium uppercase text-md">{{$step['heading']}}</h3>
                                    @if(auth()->check())
                                        <x-dialog :label="__('Partager l\'étape')">
                                            <x-slot name="button">
                                                <x-tabler-share-2 class="text-cyan-900 h-5 opacity-75"/>
                                            </x-slot>
                                            <x-slot name="title">
                                                Partager la recette
                                            </x-slot>
                                            <x-share-link :url="route('recipe.api.share', ['recipe' => $recipe])" :suffix="'#step-'.$loop->iteration">
                                                Partager l'étape <code class="bg-gray-200 py-0.5 px-1">{{$step['heading']}}</code> :
                                            </x-share-link>
                                        </x-dialog>
                                    @endif
                                </div>
                                <p class="text-sm font-normal">{{$step['text']}}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>

            <section id="author" class="flex items-start gap-6 mb-10">
                <div class="flex shrink-0 justify-center items-center w-16 h-16 bg-cyan-600 rounded-full">
                    <x-tabler-user class="text-white w-10 h-10"/>
                </div>
                <div class="mt-1 w-full">
                    <p class="font-medium capitalize">{{$recipe->author}}</p>
                    <p class="font-extralight tracking-wide max-w-[90%]">
                        {{$recipe->author_note ?? "Aucun commentaire de l'auteur sur la recette"}}
                    </p>
                </div>
            </section>

        </div>
    </div>
    @push('styles')
        <style>
            .flash {
                animation: flash-animation 1.5s ease-in-out 0s 1 alternate;
            }

            @keyframes flash-animation {
                0% { background-color: transparent; }
                50% { background-color: #ffff87; }
                100% { background-color: transparent; }
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('ingredients', () => ({
                    open: true,
                    ingredients: @json($recipe->ingredients),
                    default_servings: @json($recipe->people),
                    servings: @json($recipe->people),
                    setServings(n) {
                        this.servings = Math.max(1, Number(n) || 1);
                        const ratio = this.default_servings ? this.servings / this.default_servings : 1;
                        this.ingredients.forEach(ing => {
                            const qty = Number(ing.quantity);
                            if (!qty) { ing.new_quantity = null; return; }
                            const scaled = Math.round(qty * ratio * 100) / 100;
                            ing.new_quantity = String(ing.quantity_text).replace(String(ing.quantity), scaled);
                        });
                    }
                }));
            });


            function flashElement(id) {
                const element = document.getElementById(id);
                if (element) {
                    element.classList.add('flash');
                    // Optionally remove the class after the animation completes to prevent replay on subsequent hash changes
                    setTimeout(() => {
                        element.classList.remove('flash');
                    }, 3000); // 3s = duration of the animation
                }
            }

            function checkHashOnLoad() {
                const hash = window.location.hash.substring(1); // Remove the '#' from the hash

                if (hash) {
                    flashElement(hash);
                }
            }

            // Check the URL on page load
            window.onload = checkHashOnLoad;
        </script>
    @endpush
@endsection
