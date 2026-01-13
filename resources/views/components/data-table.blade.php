@props([
'columns' => [],
'rows' => [],
'emptyMessage' => 'Belum ada data tersedia',
])

<div class="overflow-x-auto">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
            <tr>
                @foreach($columns as $column)
                <th class="px-6 py-4 {{ $column['class'] ?? '' }}">
                    {{ $column['label'] }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($rows as $row)
            <tr class="hover:bg-gray-50 transition-colors">
                @foreach($columns as $column)
                <td class="px-6 py-4 {{ $column['rowClass'] ?? '' }}">
                    @php
                    $key = $column['key'];
                    $value = data_get($row, $key);
                    $type = $column['type'] ?? 'text';
                    @endphp

                    @if($type === 'avatar')
                    <div class="flex items-center gap-3 font-medium text-gray-900">
                        <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-xs font-bold text-indigo-600 border border-indigo-100">
                            {{ $column['avatar_text'] ?? ($value ? substr($value, 0, 1) : '?') }}
                        </div>
                        {{ $value }}
                    </div>
                    @elseif($type === 'badge')
                    @php
                    $badgeColor = data_get($row, $column['badge_color_key'] ?? 'color', 'gray');
                    $colorClasses = [
                    'blue' => 'bg-blue-100 text-blue-800',
                    'green' => 'bg-green-100 text-green-800',
                    'red' => 'bg-red-100 text-red-800',
                    'yellow' => 'bg-yellow-100 text-yellow-800',
                    'orange' => 'bg-orange-100 text-orange-800',
                    'indigo' => 'bg-indigo-100 text-indigo-800',
                    'gray' => 'bg-gray-100 text-gray-800',
                    ];
                    $currentClass = $colorClasses[$badgeColor] ?? $colorClasses['gray'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $currentClass }}">
                        {{ $value }}
                    </span>
                    @elseif($type === 'date')
                    <span class="text-gray-400 capitalize">{{ $value }}</span>
                    @else
                    <span class="{{ $column['textClass'] ?? 'text-gray-500' }}">{{ $value }}</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($columns) }}" class="px-6 py-12">
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <x-heroicon-o-inbox class="w-8 h-8 text-gray-300" />
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">{{ $emptyMessage }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Data yang anda cari mungkin belum tersedia saat ini.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>