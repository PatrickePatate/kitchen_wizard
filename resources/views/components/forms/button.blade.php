@props([
    'type' => 'submit',
    'name' => null,
    'variant' => 'primary',
    'label' => null,
    'icon' => null,
])
<button type="{{ $type }}" {{ $attributes->class(['btn-'.$variant]) }}>
    @if($icon) @svg($icon, ['class' => 'w-4 h-4', 'aria-hidden' => 'true']) @endif {{ __($label ?? '') }}
</button>
