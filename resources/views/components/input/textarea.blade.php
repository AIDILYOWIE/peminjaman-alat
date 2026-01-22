@props([
'label' => null,
'name' => '',
'placeholder' => '',
'value' => '',
'required' => false,
'disabled' => false,
'description' => null,
'rows' => 3
])

<div class="space-y-1.5">
    @if($label)
    <label for="{{ $name }}" class="block text-xs font-semibold text-gray-500 mb-2">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif

    <div class="relative group">
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge(['class' => 'block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border transition-all duration-200 hover:border-gray-300']) !!}>{{ old($name, $value) }}</textarea>
    </div>

    @if($description)
    <p class="text-xs text-gray-500">{{ $description }}</p>
    @endif

    @error($name)
    <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>