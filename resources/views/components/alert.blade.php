@props([
    /** @var 'info'|'warning'|'danger'|'success' $type  */
    'type' => 'info',
])
@php
    $bgColor = match($type) {
        'warning' => 'border border-yellow-500',
        'danger' => 'border-2 border-red-500',
        'success' => 'border border-green-500',
        default => 'border border-blue-500',
    };
    $icon = match($type) {
        'warning' => 'tabler-exclamation-circle',
        'danger' => 'tabler-alert-triangle',
        'success' => 'tabler-check',
        default => 'tabler-info-hexagon',
    };
@endphp
<div class="md:col-span-2">
    <div class="{{$bgColor}} text-dark p-4 rounded-xl flex items-center gap-4">
        <div>
            {{ svg($icon) }}
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
