@props([
'label' => null,
'name' => '',
'type' => 'text',
'placeholder' => '',
'value' => '',
'required' => false,
'disabled' => false,
'description' => null,
'icon' => null
])

<div class="space-y-1.5">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif

    <div class="relative group">
        @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
            <x-dynamic-component :component="$icon" class="h-4 w-4" />
        </div>
        @endif

        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ $value }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge(['class' => 'block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border' . ($icon ? ' pl-10' : '')]) !!}
        >
    </div>

    @if($description)
    <p class="text-xs text-gray-500">{{ $description }}</p>
    @endif

    @error($name)
    <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>