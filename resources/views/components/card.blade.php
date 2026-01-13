@props([
'title',
'subtitle' => null
])

<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    @if(isset($title) || isset($subtitle))
    <div class="flex items-center justify-between mb-6">
        @isset($title)
        <h3 class="text-lg font-bold text-gray-800">{{ $title }}</h3>
        @endisset

        @isset($subtitle)
        <span class="text-xs text-gray-400">{{ $subtitle }}</span>
        @endisset
    </div>
    @endif

    {{ $slot }}
</div>