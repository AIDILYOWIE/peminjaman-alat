@props([
'label' => null,
'name' => '',
'options' => [],
'selected' => null,
'placeholder' => 'Pilih opsi...',
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

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge(['class' => 'block w-full  border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border transition-all' . ($icon ? ' pl-10' : '')]) !!}
            >
            @if($placeholder)
            <option value="" disabled {{ is_null($selected) ? 'selected' : '' }}>{{ $placeholder }}</option>
            @endif

            @forelse($options as $value => $optionLabel)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>{{ $optionLabel }}</option>
            @empty
            {{ $slot }}
            @endforelse
        </select>
    </div>

    @if($description)
    <p class="text-xs text-gray-500">{{ $description }}</p>
    @endif

    @error($name)
    <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>