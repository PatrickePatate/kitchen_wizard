<a href="{{route('shopping-list')}}" title="{{__('Ma liste de courses')}}" class="relative flex items-center justify-center w-10 h-10 rounded-full text-white hover:bg-white/10 transition-colors">
    <x-tabler-shopping-cart class="h-6 w-6"></x-tabler-shopping-cart>
    @if($count > 0)
        <span class="absolute top-0.5 right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-blue-600 text-white text-[0.65rem] font-semibold leading-none">
            {{$count > 99 ? '99+' : $count}}
        </span>
    @endif
</a>
