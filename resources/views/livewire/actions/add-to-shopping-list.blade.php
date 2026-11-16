<div class="{{$wrapperClass}}">
    @if($recipe?->isInShoppingListOf(auth()->user()))
        <button type="button" wire:click.debounce="remove()" aria-pressed="true" aria-label="{{ __('Remove from shopping list') }}" class="cursor-pointer">
            <x-tabler-shopping-cart-filled class="{{$iconClass}}"></x-tabler-shopping-cart-filled>
        </button>
    @else
        <button type="button" wire:click.debounce="add()" aria-pressed="false" aria-label="{{ __('Add to shopping list') }}" class="cursor-pointer">
            <x-tabler-shopping-cart class="{{$iconClass}}"></x-tabler-shopping-cart>
        </button>
    @endif
</div>
