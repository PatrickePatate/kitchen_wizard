@use(App\Models\RecipeDailySelection;use Carbon\Carbon)
@use(App\Services\WeatherService)
@use(App\MealTypeEnum)
<div class="relative container mx-auto min-h-dvh">
    <!-- Feed header -->
    <div class="max-w-3xl mx-auto border-x px-6 pt-6 pb-4 border-b">
        <div class="flex justify-between items-center">
            <h1 class="font-sans font-medium text-xl mb-3">
                <span class="font-semibold">{{__("Aujourd'hui")}}</span>
                <br>{{Carbon::now()->translatedFormat('l d F')}}.
            </h1>
            <div x-data="{popup: { open: false }}" @click.away="popup.open = false" @keydown.escape.window="popup.open = false"
                 @mouseenter="popup.open = true" @mouseleave="popup.open = false">
                <div class="relative text-white">
                    <button type="button" @click="popup.open = true" :aria-expanded="popup.open" aria-label="{{ __('Weather') }}">
                        <img src="{{WeatherService::getIcon()}}" alt="" class="h-16">
                    </button>
                    <div x-cloak x-transition x-show="popup.open"
                         class="bg-neutral-900 rounded-lg px-3 py-2 min-w-[250px] absolute z-20 top-14 right-0">
                        <div class="mr-2 text-lg font-medium">{{WeatherService::getCity()}}</div>
                        <span class="mr-2">{{WeatherService::getTemperature()}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-3xl mx-auto border-x px-5 pt-6 pb-5 border-b">
        <!-- Day selector -->
        <div class="flex flex-nowrap overflow-x-auto overflow-y-hidden snap-x justify-start items-stretch gap-3 pb-1" role="group" aria-label="{{ __('Choose a day') }}">
            @foreach($lastWeekSelections as $prevSelection)
                @php $isActive = $selectionDay?->isSameDay($prevSelection->created_at); @endphp
                <div class="snap-start flex items-stretch rounded-full text-sm @if($isActive) bg-neutral-900 text-white @else bg-gray-200 text-neutral-900 @endif">
                    <button type="button" wire:click="selectDay('{{$prevSelection->created_at->format('Y-m-d')}}')" @if($isActive) aria-current="date" @endif class="whitespace-nowrap px-3 py-2 rounded-full cursor-pointer @if($isActive) font-semibold @else hover:bg-gray-300 @endif">
                        @if($prevSelection->created_at->isToday())
                            {{__('Today')}}
                        @elseif($prevSelection->created_at->isYesterday())
                            {{__('Yesterday')}}
                        @else
                            {{$prevSelection->created_at->translatedFormat('D d M')}}
                        @endif
                    </button>
                    @if($isActive && !is_null($selection))
                        <button type="button" wire:click="addToShoppingList" wire:loading.attr="disabled" wire:target="addToShoppingList"
                                @disabled($addedToShoppingList)
                                title="{{__('Ajouter la sélection du jour à ma liste de courses')}}" aria-label="{{__('Ajouter la sélection du jour à ma liste de courses')}}"
                                class="flex items-center justify-center pl-2.5 pr-3 border-l border-white/25 rounded-r-full hover:bg-white/10 disabled:hover:bg-transparent transition-colors">
                            @if($addedToShoppingList)
                                <x-tabler-circle-check-filled class="h-4 w-4"></x-tabler-circle-check-filled>
                            @else
                                <x-tabler-shopping-cart-plus class="h-4 w-4"></x-tabler-shopping-cart-plus>
                            @endif
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div wire:loading.remove wire:target="selectDay">
        @if(!is_null($selection))
            <!-- Main Course section -->
            <section class="max-w-3xl mx-auto pb-7 border-x p-6 border-b">
                <div class="flex gap-2 items-center mb-3">
                    <div class="bg-gray-200 rounded-full p-2 w-10 h-10 flex justify-center items-center">
                        @svg(MealTypeEnum::MAIN_COURSE->getIcon())
                    </div>
                    <h2 class="font-sans uppercase font-semibold text-lg">{{__('Plat Principal')}}</h2>
                </div>
                <x-recipe-card :recipe="$main" :allow-refresh="$selection->created_at->isSameDay(Carbon::today())"/>
            </section>

            <!-- Starter section -->
            <section class="max-w-3xl mx-auto pb-7 border-x p-6 border-b">
                <div class="flex gap-2 items-center mb-3">
                    <div class="bg-gray-200 rounded-full p-2 w-10 h-10 flex justify-center items-center">
                        @svg(MealTypeEnum::STARTER->getIcon())
                    </div>
                    <h2 class="font-sans uppercase font-semibold text-lg">{{__('Entrée')}}</h2>
                </div>
                <x-recipe-card :recipe="$starter" :allow-refresh="$selection->created_at->isSameDay(Carbon::today())"/>
            </section>

            <!-- Dessert section -->
            <section class="max-w-3xl mx-auto mb-7 border-x p-6 border-b">
                <div class="flex gap-2 items-center mb-3">
                    <div class="bg-gray-200 rounded-full p-2 w-10 h-10 flex justify-center items-center">
                        @svg(MealTypeEnum::DESSERT->getIcon())
                    </div>
                    <h2 class="font-sans uppercase font-semibold text-lg">{{__('Dessert')}}</h2>
                </div>
                <x-recipe-card :recipe="$dessert" :allow-refresh="$selection->created_at->isSameDay(Carbon::today())"/>
            </section>
        @else
            <section class="max-w-3xl mx-auto border-x p-6 border-b">
                <h2 class="font-heading font-medium text-xl mb-3 text-center">{{__('No recipe suggestions found for today !')}}</h2>
                <p class="text-center text-gray-600 mb-4">{{__('Suggestions are generated every day. Try another day, or come back a bit later.')}}</p>
                <div class="flex justify-center"><a href="{{ route('search') }}" class="btn-primary">{{__('Search recipes')}}</a></div>
            </section>
        @endif
    </div>
    <div wire:loading.class.remove="hidden" wire:target="selectDay" class="hidden" role="status" aria-label="{{ __('Loading') }}">
        <section class="max-w-3xl mx-auto border-x p-6 border-b">
            <div class="skeleton h-6 w-40 mb-4"></div>
            <div class="skeleton aspect-16/10 w-full mb-3"></div>
            <div class="skeleton h-6 w-3/4"></div>
        </section>
    </div>


</div>
