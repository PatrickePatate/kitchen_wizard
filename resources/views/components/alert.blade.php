@props([
    /** @var 'info'|'warning'|'danger'|'success' $type  */
    'type' => 'info',
])
@php
    $styles = match($type) {
        'warning' => 'border-yellow-500 bg-yellow-50',
        'danger' => 'border-red-500 bg-red-50',
        'success' => 'border-green-500 bg-green-50',
        default => 'border-blue-500 bg-blue-50',
    };
    $icon = match($type) {
        'warning' => 'tabler-exclamation-circle',
        'danger' => 'tabler-alert-triangle',
        'success' => 'tabler-check',
        default => 'tabler-info-hexagon',
    };
@endphp
<div {{ $attributes->class('md:col-span-2') }} role="{{ in_array($type, ['danger', 'warning']) ? 'alert' : 'status' }}">
    <div class="border {{ $styles }} text-neutral-900 p-4 rounded-xl flex items-center gap-4">
        <div class="shrink-0" aria-hidden="true">
            {{ svg($icon) }}
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
