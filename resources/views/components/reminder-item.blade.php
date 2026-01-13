@props([
'title',
'description',
'icon',
'color' => 'indigo'
])

@php
$colorClasses = [
'indigo' => 'bg-indigo-50 text-indigo-600',
'orange' => 'bg-orange-50 text-orange-600',
'red' => 'bg-red-50 text-red-600',
'green' => 'bg-green-50 text-green-600',
'blue' => 'bg-blue-50 text-blue-600',
];

$currentColorClass = $colorClasses[$color] ?? $colorClasses['indigo'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-4']) }}>
    <div class="w-10 h-10 rounded-full {{ $currentColorClass }} flex items-center justify-center flex-shrink-0">
        <x-dynamic-component :component="$icon" class="w-5 h-5" />
    </div>
    <div>
        <h4 class="text-sm font-semibold text-gray-900">{{ $title }}</h4>
        <p class="text-xs text-gray-500 mt-0.5">{{ $description }}</p>
    </div>
</div>