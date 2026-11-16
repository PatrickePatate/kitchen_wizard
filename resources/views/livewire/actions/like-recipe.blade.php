<div class="absolute right-3 top-3">
    @if($recipe?->isLikedBy(auth()->user()))
        <button type="button" wire:click.debounce="unlike()" aria-label="{{ __('Remove from favorites') }}" aria-pressed="true" class="cursor-pointer">
            <x-tabler-heart-filled class="h-7 w-7 text-white drop-shadow-xl"></x-tabler-heart-filled>
        </button>
    @else
        <button type="button" wire:click.debounce="like()" aria-label="{{ __('Add to favorites') }}" aria-pressed="false" class="cursor-pointer">
            <x-tabler-heart class="h-7 w-7 text-white drop-shadow-xl"></x-tabler-heart>
        </button>
    @endif
</div>
