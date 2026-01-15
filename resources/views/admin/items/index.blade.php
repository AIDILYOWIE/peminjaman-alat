@extends('layouts.app')

@section('header', 'Data Alat')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isEditing: false, 
    selectedItem: {},
    // Initializing form data
    form: {
        name: '',
        code: '',
        category: '',
        stock: 0,
        total_stock: 0,
        status: '',
        icon: ''
    },
    openDetail(item) {
        this.selectedItem = item;
        this.form = { ...item };
        this.detailOpen = true;
        this.isEditing = false;
    },
    closeDetail() {
        this.detailOpen = false;
        this.isEditing = false;
    },
    toggleEdit() {
        this.isEditing = !this.isEditing;
    },
    cancelEdit() {
        this.form = { ...this.selectedItem };
        this.isEditing = false;
    },
    confirmEdit() {
        // Placeholder for update logic
        this.selectedItem = { ...this.form };
        this.isEditing = false;
        // You would typically call an API here
    }
}">
    @php
    $columns = [
    [
    'label' => 'Info Alat',
    'key' => 'name',
    'component' => 'info',
    'map' => ['subtitle' => 'code', 'icon' => 'icon'],
    'class' => 'w-full'
    ],
    [
    'label' => 'Stok',
    'key' => 'stock',
    'component' => 'progress',
    'map' => ['total' => 'total_stock'],
    'align' => 'text-left',
    'class' => 'w-full sm:min-w-[200px] xl:min-w-[500px] min-w-[150px]',
    ],
    [
    'label' => 'Kategori',
    'key' => 'category',
    'component' => 'badge',
    'map' => ['color' => 'category_color'],
    'hidden' => 'hidden sm:table-cell',
    'align' => 'text-center',
    'class' => 'whitespace-nowrap w-px'
    ],
    [
    'label' => 'Status',
    'key' => 'status',
    'component' => 'badge',
    'map' => ['color' => 'status_color'],
    'hidden' => 'hidden sm:table-cell',
    'class' => 'whitespace-nowrap w-px'
    ],
    ];

    $items = [
    [
    'id' => 1,
    'code' => 'CAM-001',
    'name' => 'Sony Alpha a7 III',
    'category' => 'Kamera',
    'category_color' => 'indigo',
    'stock' => 4,
    'total_stock' => 5,
    'status' => 'Tersedia',
    'status_color' => 'green',
    'icon' => 'heroicon-o-camera'
    ],
    [
    'id' => 2,
    'code' => 'ACC-023',
    'name' => 'Tripod Manfrotto',
    'category' => 'Aksesoris',
    'category_color' => 'blue',
    'stock' => 0,
    'total_stock' => 3,
    'status' => 'Kosong',
    'status_color' => 'red',
    'icon' => 'heroicon-o-video-camera'
    ],
    [
    'id' => 3,
    'code' => 'AUD-005',
    'name' => 'Zoom H6 Recorder',
    'category' => 'Audio',
    'category_color' => 'purple',
    'stock' => 1,
    'total_stock' => 1,
    'status' => 'Tersedia',
    'status_color' => 'green',
    'icon' => 'heroicon-o-microphone'
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$items"
        paginated="true"
        searchPlaceholder="Cari alat berdasarkan nama atau kode..."
        addButtonText="Tambah Alat"
        :addButtonRoute="route('admin.items.create')"
        hasFilter="true"
        hasExport="true"
        onRowClick="openDetail($row)" />

    <x-slide-over
        open="detailOpen"
        title="Alat"
        isEditing="isEditing"
        onClose="closeDetail()"
        onToggleEdit="toggleEdit()"
        onConfirm="confirmEdit()"
        onCancel="cancelEdit()">
        <!-- Item Hero Section -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 sm:p-8 p-4 text-white">
            <div class="flex items-start justify-between sm:mb-6 mb-4">
                <div class="flex-1">
                    <div class="inline-flex items-center sm:gap-2 gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium mb-3">
                        <x-heroicon-s-cube class="w-3 h-3" />
                        <span x-text="form.code"></span>
                    </div>
                    <h3 class="sm:text-2xl text-xl font-bold sm:mb-2" x-text="form.name || 'Nama Alat'"></h3>
                    <p class="text-indigo-100 sm:text-sm text-xs" x-text="form.category"></p>
                </div>
                <div class="flex-shrink-0 w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <x-heroicon-o-camera class="w-10 h-10 text-white/80" />
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Tersedia</div>
                    <div class="sm:text-xl text-base font-bold" x-text="form.stock"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Total</div>
                    <div class="sm:text-xl text-base font-bold" x-text="form.total_stock"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Status</div>
                    <div class="sm:text-xs text-base font-bold" x-text="form.status"></div>
                </div>
            </div>
        </div>

        <!-- Form Sections -->
        <div class="sm:p-6 p-3 sm:space-y-6 space-y-3">
            <!-- Basic Information Card -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-information-circle class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Informasi Dasar</h4>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Nama Alat</label>
                        <input type="text" x-model="form.name" :disabled="!isEditing"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:outline-none transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0 disabled:text-gray-900">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Kode Alat</label>
                            <input type="text" x-model="form.code" :disabled="true"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Kategori</label>
                            <select x-model="form.category" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:appearance-none disabled:px-0">
                                <option>Kamera</option>
                                <option>Aksesoris</option>
                                <option>Audio</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Management Card -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-cube class="w-5 h-5 text-green-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Manajemen Stok</h4>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Stok Tersedia</label>
                            <input type="number" x-model="form.stock" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Total Stok</label>
                            <input type="number" x-model="form.total_stock" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Status Ketersediaan</label>

                        <!-- Mode View: Badge -->
                        <div x-show="!isEditing" class="py-1">
                            <template x-if="form.status === 'Tersedia'">
                                <x-badge color="green" value="Tersedia" />
                            </template>
                            <template x-if="form.status === 'Kosong'">
                                <x-badge color="red" value="Kosong" />
                            </template>
                            <template x-if="form.status === 'Maintenance'">
                                <x-badge color="yellow" value="Maintenance" />
                            </template>
                        </div>

                        <!-- Mode Edit: Select -->
                        <select x-show="isEditing" x-model="form.status"
                            class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            :class="{
                                        'text-green-700': form.status === 'Tersedia',
                                        'text-red-700': form.status === 'Kosong',
                                        'text-yellow-700': form.status === 'Maintenance'
                                    }">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Kosong">Kosong</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Photo Upload Card -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-photo class="w-5 h-5 text-purple-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Foto Alat</h4>
                </div>

                <div class="aspect-video relative rounded-xl bg-white flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-200 group hover:border-indigo-300 transition-colors">
                    <div class="flex flex-col items-center gap-3 text-gray-400">
                        <x-heroicon-o-camera class="w-12 h-12" />
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-900" x-text="form.name"></p>
                            <p class="text-xs text-gray-500 mt-1">Belum ada foto</p>
                        </div>
                    </div>
                    <div x-show="isEditing"
                        class="absolute inset-0 bg-gradient-to-t from-indigo-600/90 to-indigo-600/70 backdrop-blur-[2px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                        <button class="bg-white text-indigo-600 text-sm font-bold px-6 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                            <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                            Upload Foto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-slide-over>
</div>
@endsection