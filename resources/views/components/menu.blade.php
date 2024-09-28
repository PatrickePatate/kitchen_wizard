@php use App\Services\WeatherService; @endphp
<nav class="mx-auto mt-8 bg-neutral-900 text-white py-2 px-6 rounded-full max-w-[850px]">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-heading"><a href="{{route('home')}}">KitchenWizard</a></h1>
        <div x-data="{popup: { open: false }}" @click="popup.open = true" @click.away="popup.open = false" @mouseenter="popup.open = true" @mouseleave="popup.open = false">
            <div class="relative">
                <img src="{{WeatherService::getIcon()}}" alt="weather icon" class="h-12"></img>
                <div x-cloak x-transition x-show="popup.open" class="bg-neutral-900 rounded-lg px-3 py-2 min-w-[180px] absolute z-20 top-16 right-0">
                    <div class="mr-2 text-lg font-medium">{{WeatherService::getCity()}}</div>
                    <span class="mr-2">{{WeatherService::getTemperature()}}</span>
                </div>
            </div>
        </div>
    </div>
</nav>
