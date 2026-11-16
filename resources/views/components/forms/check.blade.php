@props([
    'name',
    'label' => null,
    'help' => null,
    'checked' => false,
    'required' => false,
])
@php $id = 'input-'.$name; @endphp
<div class="mb-4">
    <div class="flex items-center gap-2">
        <input id="{{ $id }}" type="checkbox" name="{{ $name }}"
               {{ $attributes->except('class') }}
               @if($checked || old($name)) checked @endif
               @if($required) required @endif
               @if($help) aria-describedby="{{ $id }}-help" @endif
               class="w-4 h-4 text-blue-600 border-gray-300 rounded-sm focus:ring-2 focus:ring-blue-500 {{ $attributes->get('class') }}">
        @if($label)
            <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
        @endif
    </div>
    @if($help)
        <p class="ms-6 mt-1 text-[0.75rem] text-gray-500" id="{{ $id }}-help">{{ $help }}</p>
    @endif
    @error($name)
        <p class="ms-6 mt-1 text-red-600 text-sm" role="alert">{{ $message }}</p>
    @enderror
</div>
