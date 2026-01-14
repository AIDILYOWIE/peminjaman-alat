@props([
'value',
'total' => 0,
])

@php
$current = (int) $value;
$totalCount = (int) $total;
$percentage = ($totalCount > 0) ? ($current / $totalCount) * 100 : 0;
$barColor = $percentage > 50 ? 'bg-green-500' : ($percentage > 0 ? 'bg-yellow-500' : 'bg-gray-300');
@endphp

<div>
    <div class="text-sm text-gray-900 font-medium">{{ $current }} / {{ $totalCount }} Unit</div>
    <div class="w-24 bg-gray-200 rounded-full h-1.5 mt-1">
        <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
    </div>
</div>