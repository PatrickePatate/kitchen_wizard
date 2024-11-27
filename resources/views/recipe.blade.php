@use(Carbon\Carbon)
@extends('layouts.app')
@section('meta')
    <meta property="og:title" content="{{$recipe->title}}" />
    <meta property="og:url" content="{{$recipe->url}}" />
    <meta property="og:image" content="{{ isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : asset('images/default_recipe_picture.webp') }}" />
@endsection
@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto mt-8">
            <a href="{{route('home')}}" class="flex gap-2 items-center mb-7">
                <x-tabler-arrow-left class="w-8 h-auto text-gray-600 "></x-tabler-arrow-left>
                <p class="font-light">Retour</p>
            </a>
            <div class="flex gap-2 items-center mb-3">
                <div class="bg-gray-200 rounded-full p-2 w-10 h-10 flex justify-center items-center">
                    @svg($recipe->meal_type?->getIcon())
                </div>
                <h2 class="font-sans uppercase font-semibold text-lg">{{$recipe->meal_type}}</h2>
            </div>

            <x-recipe-card class="mb-10" :recipe="$recipe" :carousel="true" :allowRefresh="false" :clickable="false"/>

            <div class="grid grid-cols-3 bg-neutral-100 p-3 mb-10">
                <div>
                    <p>Temps de preparation</p>
                    <span class="font-semibold">{{$recipe->times['prep']}}</span>
                </div>
                <div>
                    <p>Temps de cuisson</p>
                    <span class="font-semibold">{{$recipe->times['cook']}}</span>
                </div>
                <div>
                    <p>Temps de repos</p>
                    <span class="font-semibold">{{$recipe->times['rest_time']}}</span>
                </div>
            </div>

            <div x-data="ingredients" class="flex flex-col gap-2 p-4 bg-neutral-100 mb-6">
                <div class="flex justify-between cursor-pointer" @click="open = !open">
                    <h3 class="font-sans font-medium uppercase text-md flex items-center gap-1">
                        <x-tabler-apple class="inline" />
                        <span class="pt-1">Ingrédients :</span>
                    </h3>
                    <div>
                        <x-tabler-chevron-up x-show="open" />
                        <x-tabler-chevron-down x-show="!open" />
                    </div>
                </div>
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
                            <input type="number" min="0" class="w-14 h-8 text-center border border-neutral-300 rounded-md" @change="recalculateServings($el.value)" value="{{$recipe->people}}">
                            <span class="text-md font-sans font-thin">personne(s)</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                        <template x-for="ing in ingredients" :key="ing.label">
                            <div  class="flex flex-col flex-nowrap gap-1">
                                <h3 class="text-lg font-semibold pt-1 capitalize" x-text="ing.label"></h3>
                                <h4 class="text-sm font-normal pt-1 capitalize" x-text="ing.quantity == 0 ? '' : (ing.new_quantity || ing.quantity_text)"></h4>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div x-data="{open: false}" class="flex flex-col gap-2 p-4 bg-neutral-100 mb-12">
                <div class="flex justify-between cursor-pointer" @click="open = !open">
                    <h3 class="font-sans font-medium uppercase text-md flex items-center gap-1" >
                        <x-tabler-grill-fork class="inline" />
                        <span class="pt-1">Ustensiles :</span>
                    </h3>
                    <div>
                        <x-tabler-chevron-up x-show="open"></x-tabler-chevron-up>
                        <x-tabler-chevron-down x-show="!open" />
                    </div>
                </div>
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
                <div class="grid grid-cols-1 gap-4">
                    @foreach($recipe->steps as $step)
                        <div x-data="{hover:false}" class="step relative p-1 flex flex-col gap-2" id="step-{{$loop->index+1}}">
                            <div class="flex items-center gap-2">
                                <h3 class="font-sans font-medium uppercase text-md">
                                    {{$step['heading']}}
                                </h3>
                                <div class="hidden cursor-pointer opacity-75 share-btn">
                                    <x-tabler-share-2 @click="$clipboard('{{route('recipe', ['recipe' => $recipe])}}#step-{{$loop->index+1}}'); $flash('#copied-message-step-{{$loop->index+1}}')" class="text-cyan-900 h-5"/>
                                </div>
                                <div class="hidden px-2 py-1 bg-neutral-800 text-white text-[0.7rem]" id="copied-message-step-{{$loop->index+1}}">Copié !</div>
                            </div>

                            <p class="text-sm font-normal">{{$step['text']}}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section id="author" class="flex items-start gap-6 mb-10">
                <div class="flex justify-center items-center w-20 h-16 bg-cyan-600 rounded-full">
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
            .step:hover .share-btn {
                transition: ease-in-out 0.5s;
                display: inline-block;
            }
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
                    recalculateServings(servings) {
                        this.ingredients.forEach(ing => {
                            if(this.default_servings !== 0){
                                ing.new_quantity = ing.quantity_text.replace(ing.quantity, Math.round(ing.quantity / this.default_servings * servings));
                            } else{
                                ing.new_quantity =  ing.quantity_text.replace(Math.round(ing.quantity * servings));
                            }
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
