@props([
'title',
'value',
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

<div {{ $attributes->merge(['class' => 'bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow']) }}>
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $value }}</h3>
        </div>
        <div class="p-2 rounded-lg {{ $currentColorClass }}">
            @if(isset($icon))
            <x-dynamic-component :component="$icon" class="w-6 h-6" />
            @else
            {{ $icon_slot ?? '' }}
            @endif
        </div>
    </div>
    @if($slot->isNotEmpty())
    <div class="mt-4 flex items-center text-sm">
        {{ $slot }}
    </div>
    @endif
</div>