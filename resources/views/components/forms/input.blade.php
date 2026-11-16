@props([
    'type' => 'text',
    'name',
    'label' => null,
    'help' => null,
    'value' => null,
    'required' => false,
    'placeholder' => null,
])
@php
    $id = 'input-'.$name;
    $hasError = $errors->has($name);
    $describedBy = trim(($help ? $id.'-help ' : '').($hasError ? $id.'-error' : ''));
    $isPassword = $type === 'password';
@endphp
<div {{ $attributes->only('class')->class('mb-4') }} @if($isPassword) x-data="{ show: false }" @endif>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}@if($required)<span class="text-red-600" aria-hidden="true"> *</span>@endif
        </label>
    @endif
    <div class="relative">
        <input id="{{ $id }}"
               @if($isPassword) :type="show ? 'text' : 'password'" @else type="{{ $type }}" @endif
               name="{{ $name }}"
               {{ $attributes->except('class') }}
               @if($placeholder) placeholder="{{ $placeholder }}" @endif
               @unless($isPassword) value="{{ old($name, $value) }}" @endunless
               @if($required) required aria-required="true" @endif
               @if($hasError) aria-invalid="true" @endif
               @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
               class="mt-1 block w-full px-3 py-2 border rounded-md shadow-xs focus:outline-hidden focus:ring-2 sm:text-sm {{ $isPassword ? 'pe-10' : '' }} {{ $hasError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }}">
        @if($isPassword)
            <button type="button" @click="show = !show" :aria-label="show ? '{{ __('Hide password') }}' : '{{ __('Show password') }}'"
                    class="absolute inset-y-0 right-2 top-1 flex items-center text-gray-500 hover:text-gray-800">
                <x-tabler-eye class="w-5 h-5" x-show="!show" />
                <x-tabler-eye-off class="w-5 h-5" x-show="show" x-cloak />
            </button>
        @endif
    </div>
    @if($help)
        <p class="ms-1 mt-1 text-[0.75rem] text-gray-500" id="{{ $id }}-help">{{ $help }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-red-600 text-sm flex items-center gap-1" role="alert" id="{{ $id }}-error">
            <x-tabler-alert-circle class="w-4 h-4 shrink-0" /> {{ $message }}
        </p>
    @enderror
</div>
