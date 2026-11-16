@php use App\Models\Recipe; @endphp
@props([
    /** @var Recipe $recipe */
    'recipe',
    'allowRefresh'=>true,
    'clickable'=>true,
    'carousel'=>false,
    'lazy'=>true,
])
@php $pictures = $recipe->picture_urls; $main = $recipe->main_picture_url; @endphp
<article x-data="{ currentSrc : @js($main) }" class="w-full relative {{$attributes->get('class')}}" data-recipeid="{{$recipe->id}}">
    <div class="mb-2 relative">
        @if($clickable)
            <a href="{{ route('recipe', ['recipe'=>$recipe]) }}" tabindex="-1" aria-hidden="true">
                <img x-ref="mainPicture" src="{{ $main }}" alt="{{ $recipe->title }}" @if($lazy) loading="lazy" @endif class="w-full aspect-4/3 sm:aspect-16/10 object-cover object-center rounded-2xl">
            </a>
        @else
            <img x-ref="mainPicture" src="{{ $main }}" alt="{{ $recipe->title }}" class="w-full aspect-4/3 sm:aspect-16/10 object-cover object-center rounded-2xl">
        @endif
        @if(auth()->check())
            <livewire:actions.like-recipe :recipe="$recipe" :key="'like-'.$recipe->id" />
        @endif
        @if($carousel && count($pictures) > 1)
            <div class="py-3 flex justify-start items-center gap-2 overflow-x-auto">
                @foreach($pictures as $picture)
                    <button type="button" @click="$refs.mainPicture.setAttribute('src', @js($picture)); currentSrc = @js($picture)" aria-label="{{ __('Show picture :n', ['n' => $loop->iteration]) }}" class="shrink-0 rounded-lg" :class="currentSrc === @js($picture) ? 'ring-2 ring-amber-400' : ''">
                        <img src="{{ $picture }}" alt="" loading="lazy" class="w-16 h-16 object-cover object-center rounded-lg">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
    <div class="flex flex-col">
        <div class="flex justify-between items-start gap-2">
            <h2 class="text-xl font-semibold uppercase tracking-wide">
                @if($clickable)
                    <a href="{{ route('recipe', ['recipe'=>$recipe]) }}">{{ $recipe->title }}</a>
                @else
                    {{ $recipe->title }}
                @endif
            </h2>
            <div class="flex flex-nowrap items-center gap-1 shrink-0">
                <span class="text-lg font-extralight text-nowrap uppercase">{{ $recipe->total_time }}</span>
                <x-tabler-clock class="w-6" aria-hidden="true" />
            </div>
        </div>

        <div class="shrink flex justify-between items-end gap-1">
            <div class="flex flex-nowrap items-center pt-1 gap-1 -ms-1">
                <x-tabler-currency-euro class="w-6" aria-hidden="true" />
                <span class="text-lg font-extralight uppercase text-nowrap">{{ $recipe->price }}</span>
            </div>
            <div class="flex flex-nowrap items-center gap-1">
                <span class="text-lg font-extralight pt-1 uppercase text-nowrap">{{ $recipe->difficulty?->getLabel() }}</span>
                <x-tabler-chef-hat class="w-6" aria-hidden="true" />
            </div>
        </div>
    </div>
    @if($allowRefresh || $clickable)
        <div class="flex items-stretch mt-4 -ms-1 gap-3">
            @if($allowRefresh)
                <button type="button" wire:loading.class.remove="cursor-pointer" wire:loading.class="cursor-progress" wire:loading.attr="disabled" wire:target="refreshMeal" @click="$wire.refreshMeal('{{$recipe->meal_type}}')" class="btn-secondary">
                    <x-tabler-reload wire:target="refreshMeal" wire:loading.class="animate-spin"></x-tabler-reload>
                    {{ __('Changer') }}
                </button>
            @endif
            @if($clickable)
                <a href="{{route('recipe', ['recipe'=>$recipe])}}" class="btn-primary w-full">
                    <x-tabler-player-play></x-tabler-player-play>
                    {{ __('On passe en cuisine ?') }}
                </a>
            @endif
        </div>
    @endif
</article>
