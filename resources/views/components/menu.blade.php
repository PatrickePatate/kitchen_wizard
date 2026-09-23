@php use App\Services\WeatherService; @endphp
<nav x-data="{search:{open:false}}" class="bg-neutral-900 text-white">
    <div class="flex justify-between items-center gap-2 py-2.5 px-3 sm:px-6">
        <h1 class="font-heading shrink-0">
            <a href="{{route('home')}}" class="block">
                <x-icon-logo class="w-32 sm:w-48 text-white"></x-icon-logo>
            </a>
        </h1>

        <div class="flex items-center gap-0.5 sm:gap-1">
            <a href="{{route('likes')}}" title="{{__('Mes recettes favorites')}}" class="flex items-center justify-center w-10 h-10 rounded-full text-white hover:bg-white/10 transition-colors">
                <x-tabler-heart-filled class="h-6 w-6"></x-tabler-heart-filled>
            </a>

            @if(auth()->check())
                <livewire:shopping-list-badge />
            @else
                <a href="{{route('shopping-list')}}" title="{{__('Ma liste de courses')}}" class="flex items-center justify-center w-10 h-10 rounded-full text-white hover:bg-white/10 transition-colors">
                    <x-tabler-shopping-cart class="h-6 w-6"></x-tabler-shopping-cart>
                </a>
            @endif

            <div class="relative flex items-center">
                <form action="{{route('search')}}" class="hidden sm:flex items-center">
                    <div class="relative">
                        <input type="search" @keydown.enter="$el.parentElement.parentElement.submit()" class="w-44 lg:w-56 px-3 pe-9 py-2 bg-white border border-transparent rounded-full shadow-xs focus:outline-hidden focus:ring-2 focus:ring-blue-600 text-sm text-black" placeholder="{{__('Search')}}" name="query" value="{{request()->get('query')}}">
                        <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 flex items-center justify-center w-7 h-7 rounded-full text-gray-500 hover:text-black">
                            <x-tabler-search class="h-4 w-4"></x-tabler-search>
                        </button>
                    </div>
                </form>
                <button type="button" @click="search.open = !search.open" title="{{__('Search')}}" class="sm:hidden flex items-center justify-center w-10 h-10 rounded-full text-white hover:bg-white/10 transition-colors">
                    <x-tabler-search class="h-6 w-6"></x-tabler-search>
                </button>
            </div>

            @if(auth()->check())
                <div x-data="{popup: { open: false }}" @click="popup.open = !popup.open" @click.away="popup.open = false" class="relative ms-1 sm:ms-2">
                    <button type="button" class="block rounded-full ring-offset-2 ring-offset-neutral-900 focus:outline-hidden focus:ring-2 focus:ring-blue-600">
                        <img class="w-9 h-9 rounded-full cursor-pointer" src="{{Auth::user()->avatar}}"  alt="avatar"/>
                    </button>
                    <div x-cloak x-transition x-show="popup.open" class="bg-neutral-800 rounded-xl p-2 mt-2 min-w-[190px] absolute z-20 top-full right-0 shadow-lg ring-1 ring-white/10">
                        <a href="{{route('profile')}}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-base font-medium hover:bg-white/10 transition-colors">
                            <x-tabler-user class="text-gray-300 h-5 w-5"></x-tabler-user>
                            {{__('Profile')}}
                        </a>
                        <hr class="border-white/10 my-1">
                        <a href="{{route('logout')}}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-base font-medium hover:bg-white/10 transition-colors">
                            <x-tabler-logout class="text-gray-300 h-5 w-5"></x-tabler-logout>
                            {{__('Logout')}}
                        </a>
                    </div>
                </div>
            @else
                <div class="ms-1 sm:ms-2">
                    <a href="{{route('register')}}" class="flex items-center gap-1.5 text-sm font-medium text-black bg-white hover:bg-gray-100 px-3 sm:px-4 py-2 rounded-full transition-colors">
                        <x-tabler-login-2 class="h-4 w-4 sm:hidden"></x-tabler-login-2>
                        <span class="hidden sm:inline-block">{{__('Register')}}</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <div x-cloak x-transition x-show="search.open" class="bg-neutral-900 px-3 pb-3 sm:hidden">
        <form action="{{route('search')}}" class="relative flex items-center">
            <input type="search" @keydown.enter="$el.parentElement.submit()" class="block w-full px-3 pe-9 py-2 bg-white border border-transparent rounded-full shadow-xs focus:outline-hidden focus:ring-2 focus:ring-blue-600 text-sm text-black" placeholder="{{__('Search')}}" name="query" value="{{request()->get('query')}}">
            <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 flex items-center justify-center w-7 h-7 rounded-full text-gray-500 hover:text-black">
                <x-tabler-search class="h-4 w-4"></x-tabler-search>
            </button>
        </form>
    </div>
</nav>
