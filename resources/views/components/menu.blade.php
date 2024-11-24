@php use App\Services\WeatherService; @endphp
<nav x-data="{search:{open:false}}" class="bg-neutral-900 text-white py-1 px-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-heading"><a href="{{route('home')}}">KitchenWizard</a></h1>

        <div class="flex items-center gap-3 py-1">
            <div>
                <form action="{{route('search')}}" class="relative flex items-center gap-3">
                    <input type="search" @keydown.enter="$el.parent.submit" class="mt-1 hidden sm:block w-full px-2 pe-9 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 text-sm text-black" placeholder="Search" name="q" value="">
                    <x-tabler-search @click="search.open = !search.open" class="h-5 text-white sm:text-black absolute right-2 top-50"></x-tabler-search>
                </form>
            </div>
            <div x-data="{popup: { open: false }}" @click="popup.open = !popup.open" @click.away="popup.open = false">
                <div class="relative">
                    <img class="w-9 h-9 rounded-full cursor-pointer" src="{{Auth::user()->avatar}}"  alt="avatar"/>
                    <div x-cloak x-transition x-show="popup.open" class="bg-neutral-900 rounded-lg px-3 py-2 -mt-1 min-w-[180px] absolute z-20 top-14 right-0">
                        <div class="text-lg font-medium mb-1">
                            <a href="{{route('profile')}}" class="flex items-center">
                                <x-tabler-user class="text-gray-200 h-5 inline-block"></x-tabler-user>
                                {{__('Profile')}}
                            </a>
                        </div>
                        <hr class="border-[0.5] border-gray-600 my-2">
                        <div class="">
                            <a href="{{route('logout')}}" class="flex items-center">
                                <x-tabler-logout class="text-gray-200 h-5 inline-block"></x-tabler-logout>
                                {{__('Logout')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div x-cloak x-transition x-show="search.open" class="bg-neutral-900 text-white py-1 px-3 sm:hidden">
        <form action="{{route('search')}}" class="relative flex items-center gap-3">
            <input type="search" @keydown.enter="$el.parent.submit" class="mt-1 block w-full px-2 pe-9 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 text-sm text-black" placeholder="Search" name="q" value="">
        </form>
    </div>

</nav>
