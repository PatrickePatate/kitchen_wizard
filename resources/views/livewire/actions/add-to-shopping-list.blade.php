<div class="{{$wrapperClass}}">
    <span class="cursor-pointer">
         @if($recipe?->isInShoppingListOf(auth()->user()))
            <x-tabler-shopping-cart-filled wire:click.debounce="remove()" class="{{$iconClass}}"></x-tabler-shopping-cart-filled>
        @else
            <x-tabler-shopping-cart wire:click.debounce="add()" class="{{$iconClass}}"></x-tabler-shopping-cart>
        @endif
    </span>
</div>
