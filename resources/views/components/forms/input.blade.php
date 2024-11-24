@props([
    'type' => 'text',
    'name',
    'label' => null,
    'help' => null,
    'value' => null,
    'required' => false,
    'placeholder' => null,
])
<div {{$attributes->class('mb-4')}}>
    @if($label)
        <label for="input-{{$name}}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <input id="input-{{$name}}" type="{{$type}}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500  sm:text-sm {{$attributes->get('class')}}" {{$attributes->except('class')}} @if($placeholder) placeholder="{{$placeholder}}" @endif @if($value) value="{{$value}}" @endif name="{{$name}}" value="{{ old($name) }}" @if($required)required @endif>
    @if($help)
        <p class="ms-1 mt-1 text-[0.75rem] text-gray-500" id="email-description">{{ $help }}</p>
    @endif
    @error($name)
    <span class="text-red-500 text-sm" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>
