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
'loading' => false,
'exportRoute' => null,
// New Extra Action Props
'canExport' => 'false',
'canImport' => 'false',
'templateRoute' => '',
])

@php
$hasExtraActions = ($canExport === 'true' || $canImport === 'true' || $templateRoute);
@endphp

<div class="space-y-4">
    {{-- Header / Action Bar --}}
    @if($searchPlaceholder || $addButtonRoute || $title || isset($headerActions))
    <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center sm:gap-4 gap-3"
        x-data="{ 
            search: '{{ request('search') }}',
            doSearch() {
                let url = new URL(window.location.href);
                if (this.search) {
                    url.searchParams.set('search', this.search);
                } else {
                    url.searchParams.delete('search');
                }
                url.searchParams.delete('page'); // Reset to page 1 on search
                window.location.href = url.toString();
            },
            doExport() {
                let exportUrl = '{{ $exportRoute }}';
                if (!exportUrl) return;
                
                let url = new URL(exportUrl, window.location.origin);
                if (this.search) {
                    url.searchParams.set('search', this.search);
                }
                window.location.href = url.toString();
            }
        }">
        @if($title)
        <h2 class="{{ $titleClass }} font-bold text-gray-800">{{ $title }}</h2>
        @endif

        @if($searchPlaceholder)
        <div class="relative w-full sm:w-96 order-last sm:order-none">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-heroicon-o-magnifying-glass class="sm:h-5 sm:w-5 h-4 w-4 text-gray-400" />
            </div>
            <input type="text"
                x-model="search"
                @keydown.enter="doSearch()"
                class="block w-full pl-10 pr-3 sm:py-2.5 py-2 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-[14px] sm:text-sm transition-shadow shadow-sm"
                placeholder="{{ $searchPlaceholder }}">

            <template x-if="search">
                <button @click="search = ''; doSearch()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                </button>
            </template>
        </div>
        @endif

        <div class="flex items-center justify-end gap-3 w-full sm:w-auto sm:ml-auto">
            @if($hasExtraActions)
            <div class="relative" x-data="{ extraOpen: false }">
                <button @click.stop="extraOpen = !extraOpen" class="inline-flex items-center p-2 text-gray-500 bg-gray-100 rounded-lg active:scale-90 cursor-pointer" title="Lainnya">
                    <x-heroicon-o-ellipsis-vertical class="sm:h-6 sm:w-6 h-5 w-5" />
                </button>
                <div x-show="extraOpen" x-cloak
                    @click.away="extraOpen = false"
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg z-50 overflow-hidden"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95">

                    <template x-if="{{ $canExport }}">
                        <button @click.stop="doExport(); extraOpen = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-arrow-down-tray class="w-4 h-4 text-gray-400" />
                            Export Data
                        </button>
                    </template>

                    <template x-if="{{ $canImport }}">
                        <button @click.stop="$dispatch('trigger-import-modal'); extraOpen = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2 transition-colors">
                            <x-heroicon-o-arrow-up-tray class="w-4 h-4 text-gray-400" />
                            Import Data
                        </button>
                    </template>

                    @if($templateRoute)
                    <a href="{{ $templateRoute }}" @click="extraOpen = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2 transition-colors">
                        <x-heroicon-o-document-text class="w-4 h-4 text-gray-400" />
                        Unduh Template
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if($hasFilter)
            <button class="inline-flex gap-[5px] items-center px-3 py-2 sm:px-4 sm:py-2.5 border border-gray-200 shadow-sm sm:text-sm text-[12px] font-medium sm:rounded-xl rounded-[10px] text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 hover:shadow-md active:scale-95">
                <x-heroicon-o-funnel class="sm:h-5 sm:w-5 h-4 w-4 text-gray-500" />
                Filter
            </button>
            @endif

            @if($addButtonRoute)
            <a href="{{ $addButtonRoute }}"
                class="fixed bottom-6 right-6 z-40 sm:static flex gap-1 items-center p-2 justify-center sm:w-auto sm:h-auto sm:px-4 sm:py-2.5 border border-transparent shadow-2xl sm:shadow-sm text-sm font-medium rounded-full sm:rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all duration-300 hover:shadow-indigo-200 hover:shadow-lg active:scale-95 group">
                <x-heroicon-o-plus class="h-7 w-7 sm:h-5 sm:w-5" />
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
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    {{-- Skeleton Loading --}}
                    @for($i = 0; $i < 5; $i++)
                        <tr x-show="isLoading" class="animate-pulse">
                        @foreach($columns as $column)
                        @php
                        $visibilityClass = isset($column['hidden']) ? $column['hidden'] . ' ' : '';
                        $rowClass = $visibilityClass . ($column['class'] ?? $column['rowClass'] ?? '');
                        @endphp
                        <td class="px-6 py-4 {{ $rowClass }} {{ $column['align'] ?? 'text-left' }}">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            @if(($column['component'] ?? null) === 'info')
                            <div class="h-3 bg-gray-100 rounded w-1/2 mt-2"></div>
                            @endif
                        </td>
                        @endforeach
                        @if($hasActions || $routePrefix)
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg"></div>
                                <div class="w-8 h-8 bg-gray-100 rounded-lg"></div>
                            </div>
                        </td>
                        @endif
                        </tr>
                        @endfor

                        {{-- Real Data --}}
                        @forelse($rows as $row)
                        @php
                        $jsonRow = json_encode($row);
                        @endphp
                        <tr
                            x-show="!isLoading"
                            x-data="{ rowData: {{ $jsonRow }} }"
                            class="hover:bg-gray-50 transition-colors {{ $onRowClick ? 'cursor-pointer' : '' }}"
                            @if($onRowClick) @click="{{ str_replace('$row', 'rowData', $onRowClick) }}" @endif>
                            @foreach($columns as $column)
                            @php
                            $visibilityClass = isset($column['hidden']) ? $column['hidden'] . ' ' : '';
                            $rowClass = $visibilityClass . ($column['class'] ?? $column['rowClass'] ?? '');

                            $dataKey = $column['key'] ?? null;
                            $cellValue = $dataKey ? data_get($row, $dataKey) : null;

                            $componentName = $column['component'] ?? null;

                            // Smart component detection
                            $isComponent = $componentName && (
                            str_contains($componentName, '.') ||
                            str_starts_with($componentName, 'heroicon-') ||
                            view()->exists($componentName) ||
                            view()->exists("components.$componentName")
                            );

                            $isRawTag = $componentName && !$isComponent;
                            @endphp
                            <td class="px-6 py-4 min-w-0 {{ $rowClass }} {{ $column['align'] ?? 'text-left' }}">
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
                        </tr>
                        @empty
                        <tr x-show="!isLoading">
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

        {{-- Pagination Footer --}}
        @if($paginated && is_object($rows) && method_exists($rows, 'links') && $rows->hasPages())
        <div class="px-6 py-4">
            <div class="flex justify-end">
                <div class="flex-shrink-0">
                    {{ $rows->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>