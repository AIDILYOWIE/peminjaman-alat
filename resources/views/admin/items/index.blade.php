@extends('layouts.app')

@section('header', 'Data Alat')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isEditing: false, 
    isLoading: true,
    isSubmitting: false,
    selectedItem: {},
    // Initializing form data
    form: {
        id: null,
        nama: '',
        code: '',
        kategori_id: '',
        category_name: '',
        stock: 0,
        deskripsi: '',
        gambar: '',
        denda: 0,
        units: []
    },
    imageFile: null,
    imageUrl: null,
    init() {
        // Simulate loading delay for skeleton
        setTimeout(() => {
            this.isLoading = false;
        }, 1500);
    },
    handleFileSelect(e) {
        const files = e.target.files || e.dataTransfer.files;
        if (files.length > 0) {
            this.imageFile = files[0];
            if (this.imageUrl) URL.revokeObjectURL(this.imageUrl);
            this.imageUrl = URL.createObjectURL(this.imageFile);
        }
    },
    removeFile() {
        this.imageFile = null;
        if (this.imageUrl) {
            URL.revokeObjectURL(this.imageUrl);
            this.imageUrl = null;
        }
        if (this.isEditing) {
            this.form.gambar = '';
        }
        if (this.$refs.editGambar) this.$refs.editGambar.value = '';
    },
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },
    openDetail(item) {
        this.selectedItem = item;
        this.form = { 
            ...item, 
            category_name: item.kategori ? item.kategori.nama : 'Tanpa Kategori',
            kategori_id: item.kategori_id
        };
        this.detailOpen = true;
        this.isEditing = false;
    },
    closeDetail() {
        this.detailOpen = false;
        this.isEditing = false;
        this.removeFile();
    },
    toggleEdit() {
        this.isEditing = !this.isEditing;
        if (!this.isEditing) this.removeFile();
    },
    cancelEdit() {
        this.form = { ...this.selectedItem, category_name: this.selectedItem.kategori ? this.selectedItem.kategori.nama : 'Tanpa Kategori' };
        this.isEditing = false;
        this.removeFile();
    },
    confirmEdit() {
        this.isSubmitting = true;
        this.$refs.editForm.submit();
    },
    confirmDelete() {
        this.$dispatch('open-confirm', {
            title: 'Hapus Alat',
            message: 'Apakah Anda yakin ingin menghapus alat ini? Tindakan ini tidak dapat dibatalkan.',
            confirmText: 'Ya, Hapus',
            type: 'danger',
            onConfirm: () => {
                this.isSubmitting = true;
                this.$refs.deleteForm.submit();
            }
        });
    }
}">
    @php
    $columns = [
    [
    'label' => 'Info Alat',
    'key' => 'nama',
    'component' => 'info',
    'map' => ['subtitle' => 'code'],
    'class' => 'w-full'
    ],
    [
    'label' => 'Stok',
    'key' => 'stock',
    'align' => 'text-left',
    'class' => 'w-px whitespace-nowrap px-10'
    ],
    [
    'label' => 'Kategori',
    'key' => 'kategori.nama',
    'component' => 'badge',
    'params' => ['color' => 'random'],
    'hidden' => 'hidden sm:table-cell',
    'align' => 'text-center',
    'class' => 'whitespace-nowrap w-px'
    ],
    [
    'label' => 'Denda',
    'key' => 'denda',
    'component' => 'currency',
    'hidden' => 'hidden sm:table-cell',
    'align' => 'text-right',
    'class' => 'whitespace-nowrap w-px'
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$items"
        paginated="true"
        searchPlaceholder="Cari alat berdasarkan nama atau kode..."
        addButtonText="Tambah"
        :addButtonRoute="route('admin.items.create')"
        hasFilter="true"
        hasExport="false"
        :loading="true"
        onRowClick="openDetail($row)"
        canExport="true"
        canImport="true"
        :exportRoute="route('admin.items.export')"
        :templateRoute="route('admin.items.template')" />

    <x-slide-over
        open="detailOpen"
        title="Alat"
        isEditing="isEditing"
        onClose="closeDetail()"
        onToggleEdit="toggleEdit()"
        onConfirm="confirmEdit()"
        onCancel="cancelEdit()"
        onDelete="confirmDelete()">

        @php
        $categories = \App\Models\Kategori::all();
        @endphp

        <!-- Item Hero Section -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 sm:p-8 p-4 text-white">
            <div class="flex items-start justify-between sm:mb-6 mb-4">
                <div class="flex-1">
                    <div class="inline-flex items-center sm:gap-2 gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium mb-3">
                        <x-heroicon-s-cube class="w-3 h-3" />
                        <span x-text="form.code"></span>
                    </div>
                    <h3 class="sm:text-2xl text-xl font-bold" x-text="form.nama || 'Nama Alat'"></h3>
                    <p class="text-indigo-100 sm:text-sm text-xs" x-text="form.category_name"></p>
                </div>
                <div class="relative flex-shrink-0 w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center overflow-hidden border-2 border-white/30 group/img transition-all"
                    :class="isEditing ? 'cursor-pointer hover:bg-white/30' : ''"
                    @click="isEditing && $refs.editGambar.click()">

                    <!-- Remove Button (Only when editing and image exists) -->
                    <template x-if="isEditing && (form.gambar || imageFile)">
                        <button type="button" @click.stop="removeFile()"
                            class="absolute cursor-pointer top-1 right-1 p-1 bg-white text-gray-500 rounded-lg shadow-lg  transition-colors z-20">
                            <x-heroicon-m-x-mark class="w-3 h-3" />
                        </button>
                    </template>

                    <!-- Image Display (New Preview OR Existing Image) -->
                    <template x-if="imageUrl || (form.gambar && !imageFile)">
                        <img :src="imageUrl ? imageUrl : '/storage/' + form.gambar" class="w-full h-full object-cover">
                    </template>

                    <!-- Icon Display (When No Image) -->
                    <template x-if="!imageUrl && !form.gambar">
                        <div>
                            <template x-if="isEditing">
                                <x-heroicon-o-plus class="w-8 h-8 text-white/50" />
                            </template>
                            <template x-if="!isEditing">
                                <x-heroicon-o-camera class="w-10 h-10 text-white/50" />
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20 text-left">
                    <div class="text-xs text-indigo-100 mb-1">Stok</div>
                    <div class="sm:text-xl text-base font-bold" x-text="form.stock"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20 text-left">
                    <div class="text-xs text-indigo-100 mb-1">Denda</div>
                    <div class="sm:text-xl text-base font-bold" x-text="'Rp ' + Number(form.denda).toLocaleString()"></div>
                </div>
            </div>
        </div>

        <!-- Detail Sections -->
        <div class="sm:p-6 p-3 sm:space-y-6 space-y-3">
            <form x-ref="editForm" :action="'{{ route('admin.items.index') }}/' + form.id" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Hidden Input for Image -->
                <input type="file" name="gambar" x-ref="editGambar" class="sr-only" @change="handleFileSelect($event)" accept="image/*">

                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <x-heroicon-o-information-circle class="w-5 h-5 text-indigo-600" />
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">Informasi Alat</h4>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Nama Alat</label>
                            <input type="text" name="nama" x-model="form.nama" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0 @error('nama') border-red-500 @enderror">
                            @error('nama') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">SKU</label>
                                <input type="text" name="code" x-model="form.code" :readonly="true"
                                    class="w-full py-2.5 bg-gray-50 rounded-xl text-sm font-medium text-gray-900 focus:outline-none transition-all @error('code') border-red-500 @enderror">
                                @error('code') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Kategori</label>
                                <select name="kategori_id" x-model="form.kategori_id" :disabled="!isEditing"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0 disabled:appearance-none @error('kategori_id') border-red-500 @enderror">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Total Stok</label>
                                <input type="number" name="stock" x-model="form.stock" :disabled="!isEditing"
                                    class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0 @error('stock') border-red-500 @enderror">
                                @error('stock') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Denda / Hari</label>
                                <template x-if="!isEditing">
                                    <div class="py-2.5 text-sm font-medium text-gray-900" x-text="'Rp ' + Number(form.denda).toLocaleString()"></div>
                                </template>
                                <template x-if="isEditing">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="denda" x-model="form.denda"
                                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all @error('denda') border-red-500 @enderror">
                                    </div>
                                </template>
                                @error('denda') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Deskripsi</label>
                            <textarea name="deskripsi" x-model="form.deskripsi" :disabled="!isEditing" rows="3"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0 @error('deskripsi') border-red-500 @enderror"></textarea>
                            @error('deskripsi') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                <!-- Unit List Section (Asset Tracking) -->
                <div class="mt-6 bg-gray-50 rounded-2xl p-5 border border-gray-100" x-show="!isEditing && form.units && form.units.length > 0">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                                <x-heroicon-o-identification class="w-5 h-5 text-gray-400" />
                            </div>
                            <h4 class="text-sm font-bold text-gray-900">Daftar Unit (Asset Tracking)</h4>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full" x-text="form.units.length + ' Unit'"></span>
                    </div>

                    <div class="space-y-2 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="unit in form.units" :key="unit.id">
                            <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl hover:border-indigo-200 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="text-xs font-bold text-gray-900" x-text="unit.unit_code"></div>
                                        <div class="text-[10px] text-gray-500" x-text="'Kondisi: ' + (unit.condition || 'Baik')"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                                        :class="{
                                            'bg-green-100 text-green-600': unit.status === 'ready',
                                            'bg-blue-100 text-blue-600': unit.status === 'borrowed',
                                            'bg-red-100 text-red-600': unit.status === 'damaged' || unit.status === 'lost',
                                            'bg-yellow-100 text-yellow-600': unit.status === 'maintenance'
                                        }"
                                        x-text="unit.status.charAt(0).toUpperCase() + unit.status.slice(1)"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </form>

            <!-- Hidden Delete Form -->
            <form x-ref="deleteForm" :action="'{{ route('admin.items.index') }}/' + form.id" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </x-slide-over>

    <x-import-modal
        title="Import Alat"
        action="{{ route('admin.items.import') }}"
        description="Unggah file Excel (.xlsx atau .csv). Sistem akan otomatis membuat kategori jika kategori belum terdaftar." />
</div>
@endsection