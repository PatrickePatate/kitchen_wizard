@php use App\Services\WeatherService; @endphp
<nav x-data="{search:{open:false}}" class="bg-neutral-900 text-white py-1 px-6" aria-label="{{ __('Main navigation') }}">
    <div class="flex justify-between items-center gap-4">
        <a href="{{route('home')}}" class="font-heading" aria-label="{{ config('app.name') }}">
            <x-icon-logo class="w-48 text-white"></x-icon-logo>
        </a>

        <div class="flex-1 flex justify-end items-center gap-3 py-1">
            <a href="{{route('likes')}}" title="{{__('Mes recettes favorites')}}" aria-label="{{__('Mes recettes favorites')}}">
                <x-tabler-heart-filled class="h-9 text-white"></x-tabler-heart-filled>
            </a>
            <form action="{{route('search')}}" role="search" class="relative hidden sm:flex items-center">
                <label for="nav-search" class="sr-only">{{__('Search')}}</label>
                <input id="nav-search" type="search" class="mt-1 w-full px-2 pe-9 py-1.5 bg-white border border-gray-300 rounded-md shadow-xs focus:outline-hidden focus:ring-2 focus:ring-blue-500 text-sm text-black" placeholder="{{__('Search')}}" name="query" value="{{ request('query') }}">
                <button type="submit" aria-label="{{__('Search')}}" class="absolute right-2 top-1/4 mt-0.5">
                    <x-tabler-search class="h-5 text-black"></x-tabler-search>
                </button>
            </form>
            <button type="button" class="sm:hidden" @click="search.open = !search.open; $nextTick(() => search.open && $refs.mobileSearch.focus())" :aria-expanded="search.open" aria-controls="mobile-search" aria-label="{{__('Search')}}">
                <x-tabler-search x-show="!search.open" class="h-6 text-white"></x-tabler-search>
                <x-tabler-x x-show="search.open" x-cloak class="h-6 text-white"></x-tabler-x>
            </button>
            @if(auth()->check())
                <div x-data="{popup: { open: false }}" @click.away="popup.open = false" @keydown.escape.window="popup.open = false" class="relative">
                    <button type="button" @click="popup.open = !popup.open" :aria-expanded="popup.open" aria-haspopup="menu" aria-label="{{__('Account menu')}}">
                        <img class="w-9 h-9 rounded-full" src="{{Auth::user()->avatar}}" alt="" width="36" height="36"/>
                    </button>
                    <div x-cloak x-transition x-show="popup.open" role="menu" class="bg-neutral-900 rounded-lg px-3 py-2 min-w-[180px] absolute z-20 top-12 right-0 shadow-lg">
                        <a href="{{route('profile')}}" role="menuitem" class="flex items-center gap-1 text-lg font-medium mb-1">
                            <x-tabler-user class="text-gray-200 h-5 inline-block"></x-tabler-user>
                            {{__('Profile')}}
                        </a>
                        <hr class="border-gray-600 my-2">
                        <a href="{{route('logout')}}" role="menuitem" class="flex items-center gap-1">
                            <x-tabler-logout class="text-gray-200 h-5 inline-block"></x-tabler-logout>
                            {{__('Logout')}}
                        </a>
                    </div>
                </div>
            @else
                <a href="{{route('register')}}" class="text-sm text-black bg-white hover:bg-gray-100 px-2 sm:px-4 py-2 rounded-md">
                    <span class="hidden md:inline-block">{{__('Register')}}</span>
                    <span class="md:hidden"><x-tabler-login-2 class="inline"></x-tabler-login-2><span class="sr-only">{{__('Register')}}</span></span>
                </a>
            @endif
        </div>
    </div>
    <div id="mobile-search" x-cloak x-transition x-show="search.open" class="py-2 sm:hidden">
        <form action="{{route('search')}}" role="search">
            <label for="nav-search-mobile" class="sr-only">{{__('Search')}}</label>
            <input id="nav-search-mobile" x-ref="mobileSearch" type="search" class="block w-full px-2 py-1.5 bg-white border border-gray-300 rounded-md shadow-xs focus:outline-hidden focus:ring-2 focus:ring-blue-500 text-sm text-black" placeholder="{{__('Search')}}" name="query" value="{{ request('query') }}">
        </form>
    </div>
</nav>
