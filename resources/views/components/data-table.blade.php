@props([
'columns' => [],
'rows' => [],
'emptyMessage' => 'Belum ada data tersedia',
'hasActions' => false,
'routePrefix' => null, // e.g. 'admin.items'
'paginated' => false,
'title' => null,
'titleClass' => null,
'searchPlaceholder' => null,
'addButtonText' => null,
'addButtonRoute' => null,
'hasFilter' => false,
'hasExport' => false,
'onRowClick' => null,
])

<div class="space-y-4">
    {{-- Header / Action Bar --}}
    @if($searchPlaceholder || $addButtonRoute || $title || isset($headerActions))
    <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center sm:gap-4 gap-3">
        @if($title)
        <h2 class="{{ $titleClass }} font-bold text-gray-800">{{ $title }}</h2>
        @endif

        @if($searchPlaceholder)
        <div class="relative w-full sm:w-96 order-last sm:order-none">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon-o-magnifying-glass class="sm:h-5 sm:w-5 h-4 w-4 text-gray-400" />
            </div>
            <input type="text"
                class="block w-full pl-10 pr-3 sm:py-2.5 py-2 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-[14px] sm:text-sm transition-shadow shadow-sm"
                placeholder="{{ $searchPlaceholder }}">
        </div>
        @endif

        <div class="flex items-center justify-end gap-3 w-full sm:w-auto sm:ml-auto">
            @if($hasExport)
            <button class="inline-flex gap-[5px] items-center px-3 py-2 sm:px-4 sm:py-2.5 border border-transparent shadow-sm sm:text-sm text-[12px] font-medium sm:rounded-xl rounded-[10px] text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 hover:shadow-md active:scale-95">
                <x-heroicon-o-arrow-down-tray class="sm:h-5 sm:w-5 h-4 w-4" />
                Export
            </button>
            @endif

            @if($hasFilter)
            <button class="inline-flex gap-[5px] items-center px-3 py-2 sm:px-4 sm:py-2.5 border border-gray-200 shadow-sm sm:text-sm text-[12px] font-medium sm:rounded-xl rounded-[10px] text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 hover:shadow-md active:scale-95">
                <x-heroicon-o-funnel class="sm:h-5 sm:w-5 h-4 w-4 text-gray-500" />
                Filter
            </button>
            @endif

            @if($addButtonRoute)
            <a href="{{ $addButtonRoute }}"
                class="fixed bottom-6 right-6 z-40 sm:static flex items-center p-2 justify-center sm:w-auto sm:h-auto sm:px-4 sm:py-2.5 border border-transparent shadow-2xl sm:shadow-sm text-sm font-medium rounded-full sm:rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all duration-300 hover:shadow-indigo-200 hover:shadow-lg active:scale-95 group">
                <x-heroicon-o-plus class="h-7 w-7 sm:h-5 sm:w-5 transition-transform group-hover:rotate-90" />
                <span class="hidden sm:inline">{{ $addButtonText ?? 'Tambah Data' }}</span>
                <!-- Mobile Tooltip/Label (Optional, but good for UX) -->
                <span class="absolute right-16 bg-gray-900 text-white text-[10px] px-2 py-1 rounded lg:hidden opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">
                    {{ $addButtonText ?? 'Tambah Data' }}
                </span>
            </a>
            @endif

            @if(isset($headerActions))
            {{ $headerActions }}
            @endif
        </div>
    </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 sm:text-xs text-[12px] uppercase font-semibold text-gray-500">
                    <tr>
                        @foreach($columns as $column)
                        @php
                        $visibilityClass = isset($column['hidden']) ? $column['hidden'] . ' ' : '';
                        $headerClass = $visibilityClass . ($column['class'] ?? '');
                        @endphp
                        <th class="px-6 py-4 {{ $headerClass }} {{ $column['align'] ?? 'text-left' }}">
                            {{ $column['label'] }}
                        </th>
                        @endforeach
                        @if($hasActions || $routePrefix)
                        <th class="px-6 py-4 text-right">
                            <span class="sr-only">Actions</span>
                            Aksi
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                    <tr
                        class="hover:bg-gray-50 transition-colors {{ $onRowClick ? 'cursor-pointer' : '' }}"
                        @if($onRowClick) @click="{{ str_replace('$row', 'JSON.parse(\''.addslashes(json_encode($row)).'\')', $onRowClick) }}" @endif>
                        @foreach($columns as $column)
                        @php
                        $visibilityClass = isset($column['hidden']) ? $column['hidden'] . ' ' : '';
                        $rowClass = $visibilityClass . ($column['class'] ?? $column['rowClass'] ?? '');

                        $dataKey = $column['key'] ?? null;
                        $cellValue = $dataKey ? data_get($row, $dataKey) : null;

                        $componentName = $column['component'] ?? null;

                        // Smart component detection:
                        // 1. If it contains a dot, it's likely a view-based component or manual path
                        // 2. If it starts with heroicon-, it's an icon component
                        // 3. If it exists as a standalone view OR in the components folder
                        $isComponent = $componentName && (
                        str_contains($componentName, '.') ||
                        str_starts_with($componentName, 'heroicon-') ||
                        view()->exists($componentName) ||
                        view()->exists("components.$componentName")
                        );

                        $isRawTag = $componentName && !$isComponent;
                        @endphp
                        <td class="px-6 py-4 {{ $rowClass }} {{ $column['align'] ?? 'text-left' }}">
                            @if($componentName)
                            @php
                            $cellParams = ['value' => $cellValue];
                            if(isset($column['map'])) {
                            foreach($column['map'] as $prop => $mapDataKey) {
                            $cellParams[$prop] = data_get($row, $mapDataKey);
                            }
                            }
                            if(isset($column['params'])) {
                            $cellParams = array_merge($cellParams, $column['params']);
                            }
                            $cellAttributes = new \Illuminate\View\ComponentAttributeBag($cellParams);
                            @endphp

                            @if($isRawTag)
                            <{{ $componentName }} {{ $cellAttributes->merge(['class' => $column['textClass'] ?? '']) }}>
                                {{ $cellValue }}
                            </{{ $componentName }}>
                            @else
                            <x-dynamic-component :component="$componentName" :attributes="$cellAttributes" />
                            @endif
                            @else
                            <span class="{{ $column['textClass'] ?? 'text-gray-500' }}">{{ $cellValue }}</span>
                            @endif
                        </td>
                        @endforeach

                        @if($hasActions || $routePrefix)
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($routePrefix)
                            <div class="flex justify-end gap-2">
                                <a href="{{ route($routePrefix . '.edit', $row['id'] ?? $row) }}"
                                    class="text-indigo-600 hover:text-indigo-700 p-2 hover:bg-indigo-50 rounded-lg transition-all"
                                    title="Ubah">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                                <form action="{{ route($routePrefix . '.delete', $row['id'] ?? $row) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-all cursor-pointer"
                                        title="Hapus">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
                            </div>
                            @endif

                            @if(isset($actions))
                            {{ $actions }}
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($columns) + ($hasActions || $routePrefix ? 1 : 0) }}" class="px-6 py-12">
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

        @if($paginated && is_object($rows) && method_exists($rows, 'links'))
        <div class="bg-white px-4 py-3 border-t border-gray-100 sm:px-6">
            {{ $rows->links() }}
        </div>
        @endif
    </div>
</div>