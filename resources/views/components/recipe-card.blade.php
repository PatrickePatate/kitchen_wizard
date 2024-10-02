@props(['recipe','allowRefresh'=>true, 'clickable'=>true])
<div class="w-full relative {{$attributes->get('class')}}" data-recipeid="{{$recipe->id}}">
    <div>
        @if($allowRefresh)<x-refresh-recipe :type="$recipe->meal_type"/>@endif

        @if($clickable)
            <a href="{{$clickable ? route('recipe', ['recipe'=>$recipe]) : '#'}}">
                <img src="{{ isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : asset('images/default_recipe_picture.webp') }}" alt="Recipe image" class="w-full h-96 object-cover object-center">
            </a>
        @else
            <img src="{{ isset($recipe->pictures[0]) ? asset('storage/'.$recipe->pictures[0]) : asset('images/default_recipe_picture.webp') }}" alt="Recipe image" class="w-full h-96 object-cover object-center">
        @endif
    </div>
    <div class="flex justify-between">
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


            <div class="flex flex-nowrap items-center gap-1">
                <x-tabler-currency-euro class="text-gray-600 w-5" />
                <span class="text-sm font-normal pt-1 uppercase text-nowrap">{{ $recipe->price }}</span>
            </div>

        </div>
        <div class="flex-shrink flex flex-col items-end gap-1">
            <div class="flex flex-nowrap items-center gap-1 ">
                <span class="text-sm font-normal pt-1 uppercase">{{ $recipe->total_time }}</span>
                <x-tabler-clock class="text-gray-600 w-5" />
            </div>
            <div class="flex flex-nowrap items-center gap-1">
                <span class="text-sm font-normal pt-1 uppercase text-nowrap">{{ $recipe->difficulty }}</span>
                <x-tabler-chef-hat class="text-gray-600 w-5" />
            </div>
        </div>
    </div>

</div>
