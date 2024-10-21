@props(['recipe','allowRefresh'=>true, 'clickable'=>true])
<div class="w-full relative {{$attributes->get('class')}}" data-recipeid="{{$recipe->id}}">
    <div class="mb-2">
        @if($clickable)
            <a href="{{$clickable ? route('recipe', ['recipe'=>$recipe]) : '#'}}">
                <img src="{{ isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : asset('images/default_recipe_picture.webp') }}" alt="Recipe image" class="w-full h-96 object-cover object-center rounded-2xl">
            </a>
        @else
            <img src="{{ isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : asset('images/default_recipe_picture.webp') }}" alt="Recipe image" class="w-full h-96 object-cover object-center rounded-2xl">
        @endif
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
        <div class="flex items-center mt-4 -ms-1 gap-3">
            @if($allowRefresh)
                <div @click="$wire.refreshMeal('{{$recipe->meal_type}}')" class="flex gap-2 py-2 px-3 bg-gray-200 font-semibold rounded-full w-fit cursor-pointer">
                    <x-tabler-reload></x-tabler-reload>
                    Changer
                </div>
            @endif
            @if($clickable)
                    <a href="{{route('recipe', ['recipe'=>$recipe])}}" class="flex justify-center  gap-2 py-2 px-3 bg-gray-900 text-white font-semibold rounded-full w-full cursor-pointer">
                        <x-tabler-player-play></x-tabler-player-play>
                        On passe en cuisine ?
                    </a>
            @endif
        </div>
    @endif

</div>
