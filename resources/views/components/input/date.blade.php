@props([
'label' => null,
'name' => '',
'value' => '',
'required' => false,
'disabled' => false,
'description' => null
])

<div class="space-y-1.5">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif

        <input
            type="date"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge(['class' => 'block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border transition-all']) !!}
        >

    @if($description)
    @endif

    @error($name)
    <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>