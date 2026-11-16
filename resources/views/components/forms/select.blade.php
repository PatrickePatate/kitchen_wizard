@props([
    'name',
    'label' => null,
    'help' => null,
    'value' => null,
    'required' => false,
    'options' => [],
])
<div {{$attributes->class('mb-4')}}>
    @if($label)
        <label for="input-{{$name}}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif
    <select id="input-{{$name}}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500 sm:text-sm {{$attributes->get('class')}}" {{$attributes->except('class')}} name="{{$name}}" @if($required)required @endif>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{$optionValue}}" @selected(old($name, $value) == $optionValue)>{{$optionLabel}}</option>
        @endforeach
    </select>
    @if($help)
        <p class="ms-1 mt-1 text-[0.75rem] text-gray-500" id="email-description">{{ $help }}</p>
    @endif
    @error($name)
    <span class="text-red-500 text-sm" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>
