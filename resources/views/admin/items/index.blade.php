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
        denda: 0
    },
    imageFile: null,
    imageUrl: null,
    init() {
        // Simulate loading delay for skeleton
        setTimeout(() => {
            this.isLoading = false;
        }, 1500);
        this.$watch('form.nama', () => this.generateCode());
        this.$watch('form.stock', () => this.generateCode());
    },
    generateCode() {
        if (!this.isEditing) return;
        if (this.form.nama && this.form.nama.length >= 2) {
            let prefix = this.form.nama.substring(0, 2).toUpperCase();
            this.form.code = prefix + '-' + (this.form.stock || 0);
        }
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
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Kode Alat</label>
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
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Stok</label>
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
            </form>

            <!-- Hidden Delete Form -->
            <form x-ref="deleteForm" :action="'{{ route('admin.items.index') }}/' + form.id" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </x-slide-over>

    <!-- Import Modal -->
    <div x-data="{ open: false }"
        @open-import.window="open = true"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="open = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form action="{{ route('admin.items.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Import Alat</h3>
                            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <x-heroicon-o-x-mark class="w-6 h-6" />
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                <p class="text-xs text-indigo-700 leading-relaxed">
                                    Unggah file Excel (.xlsx atau .csv). Sistem akan otomatis membuat kategori jika kategori belum terdaftar.
                                </p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pilih File</label>
                                <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer border border-gray-200 rounded-xl p-1">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 shadow-sm text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all duration-200 active:scale-95">
                            Mulai Import
                        </button>
                        <button type="button" @click="open = false" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all duration-200 active:scale-95">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection