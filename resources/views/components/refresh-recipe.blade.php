@use(\App\Livewire\RecipeFeed)
@props(['type'])
<div class="w-10 h-10 bg-yellow-500 rounded-full absolute top-[40%] left-[-60px] flex items-center justify-center cursor-pointer" @click="$wire.refreshMeal('{{$type}}')">
    <x-tabler-refresh class="text-white" />
</div>
