@use(Carbon\Carbon)
@use(App\Services\WeatherService)
@use(App\MealTypeEnum)
<div class="container mx-auto min-h-[100dvh]">
    <!-- Feed header -->
    <div class="max-w-3xl mx-auto border-x px-6 pt-6 pb-4 border-b">
        <div class="flex justify-between items-center">
            <h1 class="font-sans font-medium text-xl mb-3">
                <span class="font-semibold">Aujourd'hui</span>
                <br>{{Carbon::now()->translatedFormat('l d F')}}.
            </h1>
            <div x-data="{popup: { open: false }}" @click="popup.open = true" @click.away="popup.open = false" @mouseenter="popup.open = true" @mouseleave="popup.open = false">
                <div class="relative text-white">
                    <img src="{{WeatherService::getIcon()}}" alt="weather icon" class="h-16">
                    <div x-cloak x-transition x-show="popup.open" class="bg-neutral-900 rounded-lg px-3 py-2 min-w-[250px] absolute z-20 top-14 right-0">
                        <div class="mr-2 text-lg font-medium">{{WeatherService::getCity()}}</div>
                        <span class="mr-2">{{WeatherService::getTemperature()}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!is_null($selection))
        <!-- Main Course section -->
        <section class="max-w-3xl mx-auto pb-7 border-x p-6 border-b ">
            <div class="flex gap-2 items-center mb-3">
                <div class="bg-gray-200 rounded-full p-2 w-10 h-10 flex justify-center items-center">
                    @svg(MealTypeEnum::MAIN_COURSE->getIcon())
                </div>
                <h2 class="font-sans uppercase font-semibold text-lg">Plat Principal</h2>
            </div>
            <x-recipe-card :recipe="$main" />
        </section>

        <!-- Starter section -->
        <section class="max-w-3xl mx-auto pb-7 border-x p-6 border-b">
            <div class="flex gap-2 items-center mb-3">
                <div class="bg-gray-200 rounded-full p-2 w-10 h-10 flex justify-center items-center">
                    @svg(MealTypeEnum::STARTER->getIcon())
                </div>
                <h2 class="font-sans uppercase font-semibold text-lg">Entrée</h2>
            </div>
            <x-recipe-card :recipe="$starter" />
        </section>

        <!-- Dessert section -->
        <section class="max-w-3xl mx-auto mb-7 border-x p-6 border-b">
            <div class="flex gap-2 items-center mb-3">
                <div class="bg-gray-200 rounded-full p-2 w-10 h-10 flex justify-center items-center">
                    @svg(MealTypeEnum::DESSERT->getIcon())
                </div>
                <h2 class="font-sans uppercase font-semibold text-lg">Dessert</h2>
            </div>
            <x-recipe-card :recipe="$dessert" />
        </section>
    @else
        <section class="max-w-3xl mx-auto  border-x p-6 border-b">
            <h1 class="font-heading font-medium text-xl mb-3 text-center">{{__('No recipe suggestions found for today !')}}</h1>
            <p class="text-center">{{__('Please check that the app is running well with your administrator.')}}</p>
        </section>
    @endif
</div>
