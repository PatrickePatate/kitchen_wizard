@php use App\Models\Recipe; @endphp
@props([
    /** @var Recipe $recipe */
    'recipe',
    'allowRefresh'=>true,
    'clickable'=>true,
    'carousel'=>false
    ])
<div x-data="{ currentSrc : '{{isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : ''}}' }" class="w-full relative {{$attributes->get('class')}}" data-recipeid="{{$recipe->id}}">
    <div class="mb-2">
        @if($clickable)
            <a href="{{$clickable ? route('recipe', ['recipe'=>$recipe]) : '#'}}">
                <img x-ref="mainPicture" src="{{isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : asset('images/default_recipe_picture.webp')}}" alt="Recipe image" class="w-full h-96 object-cover object-center rounded-2xl">
            </a>
        @else
            <img x-ref="mainPicture" src="{{isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : asset('images/default_recipe_picture.webp')}}" alt="Recipe image" class="w-full h-96 object-cover object-center rounded-2xl">
        @endif
        @if($carousel && count($recipe->pictures) > 1)
            <div class="py-3">
                <div class="flex justify-start items-center gap-2">
                    @foreach($recipe->pictures as $picture)
                        <img src="{{ asset('storage/'.$picture) }}" @click="$refs.mainPicture.setAttribute('src', $el.src); currentSrc = $el.src" x-init="$watch('currentSrc', (value) => { if(value === $el.src) { $el.classList.add('ring-2','ring-amber-400') } else { $el.classList.remove('ring-2','ring-amber-400') } })" alt="Recipe image" class="cursor-pointer w-16 h-16 object-cover object-center rounded-lg @if($loop->index === 0) ring-2 ring-amber-400 @endif ">
                    @endforeach
                </div>
            </div>
        @endif
        <livewire:actions.like-recipe :recipe="$recipe" />
    </div>
    <div class="flex flex-col">
        <div class="flex justify-between items-start">
            <div>
                @if($clickable)
                    <a href="{{$clickable ? route('recipe', ['recipe'=>$recipe]) : '#'}}">
                        <h2 class="text-xl font-semibold uppercase tracking-wide">
                            {{ $recipe->title }}
                        </h2>
                    </a>
                @else
                    <h2 class="text-xl font-semibold uppercase tracking-wide">
                        {{ $recipe->title }}
                    </h2>
                @endif
            </div>
            <div class="flex flex-nowrap items-center gap-1 ">
                <span class="text-lg font-extralight text-nowrap uppercase">{{ $recipe->total_time }}</span>
                <x-tabler-clock class=" w-6" />
            </div>
        </div>

        <div class="flex-shrink flex justify-between items-end gap-1">
            <div class="flex flex-nowrap items-center pt-1 gap-1 -ms-1">
                <x-tabler-currency-euro class=" w-6" />
                <span class="text-lg font-extralight  uppercase text-nowrap">{{ $recipe->price }}</span>
            </div>
            <div class="flex flex-nowrap items-center gap-1">
                <span class="text-lg font-extralight pt-1 uppercase text-nowrap">{{ $recipe->difficulty }}</span>
                <x-tabler-chef-hat class=" w-6" />
            </div>
        </div>
    </div>
    @if($allowRefresh || $clickable)
        <div class="flex items-stretch mt-4 -ms-1 gap-3">
            @if($allowRefresh)
                <button wire:loading.class.remove="cursor-pointer" wire:loading.class="cursor-progress" wire:loading.attr="disabled" wire:target="refreshMeal" @click="$wire.refreshMeal('{{$recipe->meal_type}}'); setTimeout(() => { currentPicture = $refs.mainPicture.getAttribute('src'); }, 500)" class="flex items-center gap-2 py-2 px-3 bg-gray-200 font-semibold rounded-full w-fit cursor-pointer">
                    <x-tabler-reload wire:target="refreshMeal" wire:loading.class="animate-spin"></x-tabler-reload>
                    Changer
                </button>
            @endif
            @if($clickable)
                    <a href="{{route('recipe', ['recipe'=>$recipe])}}" class="flex justify-center items-center gap-2 py-2 px-3 bg-gray-900 text-white font-semibold rounded-full w-full cursor-pointer">
                        <x-tabler-player-play></x-tabler-player-play>
                        On passe en cuisine ?
                    </a>
            @endif
        </div>
    @endif

</div>
