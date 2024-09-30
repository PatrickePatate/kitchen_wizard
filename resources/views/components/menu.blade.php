@php use App\Services\WeatherService; @endphp
<nav class="bg-neutral-900 text-white py-1 px-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-heading"><a href="{{route('home')}}">KitchenWizard</a></h1>

        <div class="flex items-center gap-3">
            <div x-data="{popup: { open: false }}" @click="popup.open = true" @click.away="popup.open = false" @mouseenter="popup.open = true" @mouseleave="popup.open = false">
                <div class="relative">
                    <img src="{{WeatherService::getIcon()}}" alt="weather icon" class="h-12">
                    <div x-cloak x-transition x-show="popup.open" class="bg-neutral-900 rounded-lg px-3 py-2 min-w-[180px] absolute z-20 top-14 right-0">
                        <div class="mr-2 text-lg font-medium">{{WeatherService::getCity()}}</div>
                        <span class="mr-2">{{WeatherService::getTemperature()}}</span>
                    </div>
                </div>
            </div>


                <div x-data="{popup: { open: false }}" @click="popup.open = !popup.open" @click.away="popup.open = false">
                    <div class="relative">
                        <img class="w-9 h-9 rounded-full" src="{{Auth::user()->avatar}}" />
                        <div x-cloak x-transition x-show="popup.open" class="bg-neutral-900 rounded-lg px-3 py-2 -mt-1 min-w-[180px] absolute z-20 top-14 right-0">
                            <div class="mr-2 text-lg font-medium mb-1">
                                <a href="{{route('profile')}}">
                                    <x-tabler-user class="text-gray-200 h-5 inline"></x-tabler-user>
                                    {{__('Profile')}}
                                </a>
                            </div>
                            <span class="mr-2">
                                <a href="{{route('logout')}}">
                                    <x-tabler-logout class="text-gray-200 h-5 inline"></x-tabler-logout>
                                    {{__('Logout')}}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</nav>
