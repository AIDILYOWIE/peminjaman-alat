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
    <label for="{{ $name }}" class="block text-xs font-semibold text-gray-500 mb-2">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif

    <div class="relative group">
        <div
            @if($attributes->has('::disabled'))
            x-show="!({!! $attributes->get('::disabled') !!})"
            @elseif($disabled)
            class="hidden"
            @endif
            @if($icon)
            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors"
            @endif
            >
            @if($icon)
            <x-dynamic-component :component="$icon" class="h-4 w-4" />
            @endif
        </div>

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge(['class' => 'block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-white border transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0 disabled:appearance-none disabled:text-gray-900 disabled:bg-transparent disabled:border-transparent disabled:px-0 disabled:appearance-none' . ($icon ? ' pl-10' : '')]) !!}
            @if($attributes->has('::disabled'))
            :class="{ 'pl-10': !({!! $attributes->get('::disabled') !!}), 'pl-0': {!! $attributes->get('::disabled') !!} }"
            @endif
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