@props([
'value' => null,
'icon' => 'heroicon-o-check-circle',
'color' => 'text-green-600',
'title' => 'Aksi'
])

<button type="button" class="{{ $color }} cursor-pointer p-2 rounded-lg transition-all active:scale-95 flex items-center justify-center" title="{{ $title }}">
    <x-dynamic-component :component="$icon" class="w-6 h-6" />
</button>