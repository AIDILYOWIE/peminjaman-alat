@props([
'value',
'subtitle' => null,
'icon' => 'heroicon-o-cube',
])

<div class="flex items-center">
    <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">
        <x-dynamic-component :component="$icon" class="w-6 h-6" />
    </div>
    <div class="ml-4">
        <div class="text-sm font-semibold text-gray-900">{{ $value }}</div>
        @if($subtitle)
        <div class="text-xs text-gray-500">{{ $subtitle }}</div>
        @endif
    </div>
</div>