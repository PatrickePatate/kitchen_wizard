@php use App\Services\WeatherService; @endphp
<nav class="bg-neutral-900 text-white py-1 px-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-heading"><a href="{{route('home')}}">KitchenWizard</a></h1>

        <div class="flex items-center gap-3">
            <div x-data="{popup: { open: false }}" @click="popup.open = true" @click.away="popup.open = false" @mouseenter="popup.open = true" @mouseleave="popup.open = false">
                <div class="relative">
                    <img src="{{WeatherService::getIcon()}}" alt="weather icon" class="h-12"></img>
                    <div x-cloak x-transition x-show="popup.open" class="bg-neutral-900 rounded-lg px-3 py-2 min-w-[180px] absolute z-20 top-16 right-0">
                        <div class="mr-2 text-lg font-medium">{{WeatherService::getCity()}}</div>
                        <span class="mr-2">{{WeatherService::getTemperature()}}</span>
                    </div>
                </div>
            </div>
            <a href="{{route('profile')}}">
                <img class="w-10 h-10 rounded-full" src="{{Auth::user()->avatar}}" />
            </a>
        </div>
    </div>
</nav>
