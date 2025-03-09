@props([
    'type' => 'submit',
    'name',
    'color' => 'bg-neutral-900',
    'textColor' => 'text-white',
    'label' => null,
    'icon' => null,
])

<button type="{{$type}}" {{$attributes->except("class")}} {{$attributes->class("px-3 py-1 $color $textColor rounded-md inline-flex items-center gap-2 cursor-pointer")}}>
    @if($icon) @svg($icon, ['class'=>'w-4 h-4']) @endif {{__($label??'')}}
</button>
