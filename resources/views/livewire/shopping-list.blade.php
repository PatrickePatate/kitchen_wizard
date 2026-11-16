<div>
    @if($shoppingListRecipes->isEmpty())
        <x-alert type="info">
            <p>{{__("Votre liste de courses est vide.")}}</p>
            <small>{{__('Ajoutez des recettes à votre liste en cliquant sur le panier en haut à droite des recettes.')}}</small>
        </x-alert>
    @else
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($shoppingListRecipes as $shoppingListRecipe)
                @if($shoppingListRecipe->recipe)
                    <div class="flex items-center gap-2 bg-neutral-100 rounded-full px-3 py-1.5 text-sm">
                        <span>{{$shoppingListRecipe->recipe->title}}</span>
                        <x-tabler-x wire:click="removeRecipe({{$shoppingListRecipe->recipe->id}})" class="w-4 h-4 cursor-pointer text-gray-500 hover:text-gray-800"></x-tabler-x>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="flex flex-col gap-2 p-4 bg-neutral-100 mb-6">
            @foreach($items as $item)
                @php $checked = in_array($item['key'], $checkedKeys); @endphp
                <div class="flex items-start gap-3 py-1.5 border-b border-neutral-200 last:border-0 cursor-pointer" wire:click="toggleItem('{{$item['key']}}')">
                    <input type="checkbox" class="mt-1 pointer-events-none" @checked($checked) tabindex="-1">
                    <div class="{{ $checked ? 'line-through text-gray-400' : '' }}">
                        <div>
                            <span class="font-medium">{{$item['label']}}</span>
                            @if($item['quantity_text'])
                                <span>&mdash; {{$item['quantity_text']}}</span>
                            @endif
                        </div>
                        <small class="text-gray-400">{{implode(', ', $item['recipe_titles'])}}</small>
                    </div>
                </div>
            @endforeach
        </div>

        <button wire:click="clear" wire:confirm="{{__('Voulez-vous vraiment vider votre liste de courses ?')}}" class="text-sm text-red-700 hover:underline">
            {{__('Vider la liste')}}
        </button>
    @endif
</div>
