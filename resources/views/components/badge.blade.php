@props([
'value',
'color' => 'gray',
])

@php
// Priority: Explicit prop > Attribute bag (important for x-dynamic-component with :attributes)
$badgeColor = $attributes->get('color', $color);

$colorClasses = [
'blue' => 'bg-blue-100 text-blue-800',
'green' => 'bg-green-100 text-green-800',
'red' => 'bg-red-100 text-red-800',
'yellow' => 'bg-yellow-100 text-yellow-800',
'orange' => 'bg-orange-100 text-orange-800',
'indigo' => 'bg-indigo-100 text-indigo-800',
'purple' => 'bg-purple-100 text-purple-800',
'teal' => 'bg-teal-100 text-teal-800',
'gray' => 'bg-gray-100 text-gray-800',
];

if ($badgeColor === 'random') {
$keys = array_keys($colorClasses);
// Exclude 'gray' from random to make it look "vibrant"
$randomKeys = array_filter($keys, fn($k) => $k !== 'gray');
$index = abs(crc32($value)) % count($randomKeys);
$badgeColor = array_values($randomKeys)[$index];
}

$currentClass = $colorClasses[$badgeColor] ?? $colorClasses['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $currentClass"]) }}>
    {{ $value }}
</span>