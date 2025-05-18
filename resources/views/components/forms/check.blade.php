@props([
    'name',
    'label' => null,
    'help' => null,
    'checked' => false,
    'required' => false,
])
<div class="flex items-center gap-2 mb-4">
    <input id="input-{{$name}}" type="checkbox" class="inline-block px-3 py-2 border border-gray-300 rounded-md shadow-xs focus:outline-hidden focus:ring-indigo-500  sm:text-sm {{$attributes->get('class')}}" {{$attributes->except('class')}} name="{{$name}}"  @if($required)required @endif>
    @if($label)
        <label for="input-{{$name}}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    @endif

    @if($help)
        <p class="ms-1 mt-1 text-[0.75rem] text-gray-500" id="email-description">{{ $help }}</p>
    @endif
    @error($name)
    <span class="text-red-500 text-sm" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>
