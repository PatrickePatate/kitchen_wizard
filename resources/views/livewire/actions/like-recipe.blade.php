<div class="absolute right-3 top-3">
    <span class="cursor-pointer">
         @if($recipe?->isLikedBy(auth()->user()))
            <x-tabler-heart-filled wire:click.debounce="unlike()" class="h-7 w-7 text-white drop-shadow-xl"></x-tabler-heart-filled>
        @else
            <x-tabler-heart wire:click.debounce="like()" class="h-7 w-7 text-white drop-shadow-xl"></x-tabler-heart>
        @endif
    </span>
</div>
