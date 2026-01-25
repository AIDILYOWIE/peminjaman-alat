@extends('layouts.app')

@section('header', 'Log Aktivitas')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isLoading: true,
    selectedLog: {},
    init() {
        setTimeout(() => {
            this.isLoading = false;
        }, 1000);
    },
    openDetail(log) {
        this.selectedLog = log;
        this.detailOpen = true;
    },
    closeDetail() {
        this.detailOpen = false;
    },
    getActionColor(action) {
        const colors = {
            'CREATE': 'emerald',
            'UPDATE': 'indigo',
            'DELETE': 'rose'
        };
        return colors[action] || 'gray';
    }
}">
    @php
    $columns = [
    [
    'label' => 'Waktu',
    'key' => 'created_at',
    'align' => 'text-left',
    'class' => 'w-48 whitespace-nowrap'
    ],
    [
    'label' => 'User',
    'key' => 'username',
    'align' => 'text-left',
    'class' => 'w-48'
    ],
    [
    'label' => 'Role',
    'key' => 'role',
    'component' => 'badge',
    'params' => ['color' => 'random'],
    'align' => 'text-center',
    'class' => 'w-px'
    ],
    [
    'label' => 'Aksi',
    'key' => 'aksi',
    'component' => 'badge',
    'map' => ['color' => 'status_color'],
    'align' => 'text-center',
    'class' => 'w-px'
    ],
    [
    'label' => 'Deskripsi',
    'key' => 'deskripsi',
    'align' => 'text-left',
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$logs"
        paginated="true"
        searchPlaceholder="Cari aktivitas atau user..."
        hasFilter="false"
        hasExport="false"
        :loading="true"
        onRowClick="openDetail($row)" />

    <x-slide-over
        open="detailOpen"
        title="Detail Aktivitas"
        onClose="closeDetail()"
        :hasActions="false">

        <!-- Activity Header -->
        <div :class="'relative sm:p-8 p-4 text-white bg-gradient-to-br ' + {
            'CREATE': 'from-emerald-500 to-emerald-600',
            'UPDATE': 'from-indigo-500 to-indigo-600',
            'DELETE': 'from-rose-500 to-rose-600'
        }[selectedLog.aksi] || 'from-gray-500 to-gray-600'">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold" x-text="selectedLog.aksi"></h3>
                    <p class="text-white/80 text-sm" x-text="selectedLog.created_at"></p>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border-2 border-white/30">
                    <template x-if="selectedLog.aksi === 'CREATE'">
                        <x-heroicon-o-plus-circle class="w-8 h-8 text-white" />
                    </template>
                    <template x-if="selectedLog.aksi === 'UPDATE'">
                        <x-heroicon-o-pencil-square class="w-8 h-8 text-white" />
                    </template>
                    <template x-if="selectedLog.aksi === 'DELETE'">
                        <x-heroicon-o-trash class="w-8 h-8 text-white" />
                    </template>
                </div>
            </div>
        </div>

        <!-- Activity Details -->
        <div class="sm:p-6 p-4 space-y-6">
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-user class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Pelaku Aktivitas</h4>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Username</p>
                        <p class="text-sm font-bold text-gray-900" x-text="selectedLog.username"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Role</p>
                        <p class="text-sm font-bold text-gray-900" x-text="selectedLog.role"></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-document-text class="w-5 h-5 text-amber-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Rincian Aktivitas</h4>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Deskripsi</p>
                    <p class="text-sm font-medium text-gray-700 leading-relaxed" x-text="selectedLog.deskripsi"></p>
                </div>
            </div>
        </div>
    </x-slide-over>
</div>
@endsection