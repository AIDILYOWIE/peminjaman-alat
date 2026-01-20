@props(['value'])
<span {{ $attributes->merge(['class' => 'font-medium text-gray-900']) }}>
    Rp {{ number_format((float)$value, 0, ',', '.') }}
</span>