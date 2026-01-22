@extends('layouts.app')

@section('header', 'Data Kategori')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isEditing: false, 
    isLoading: true,
    isSubmitting: false,
    selectedCategory: {},
    form: {
        id: null,
        nama: '',
        alat_count: 0,
    },
    init() {
        // Simulate loading delay for skeleton
        setTimeout(() => {
            this.isLoading = false;
        }, 1500);
    },
    openDetail(category) {
        this.selectedCategory = category;
        this.form = { ...category };
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
        this.form = { ...this.selectedCategory };
        this.isEditing = false;
    },
    confirmEdit() {
        this.isSubmitting = true;
        this.$refs.editForm.submit();
    },
    confirmDelete() {
        this.$dispatch('open-confirm', {
            title: 'Hapus Kategori',
            message: 'Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.',
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
    'label' => 'Nama Kategori',
    'key' => 'nama',
    'class' => 'w-full font-semibold'
    ],
    [
    'label' => 'Jumlah Alat',
    'key' => 'alat_count',
    'align' => 'text-center',
    'class' => 'whitespace-nowrap w-px'
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$categories"
        paginated="true"
        searchPlaceholder="Cari kategori berdasarkan nama..."
        hasFilter="true"
        hasExport="false"
        :loading="true"
        onRowClick="openDetail($row)"
        addButtonText="Tambah"
        :addButtonRoute="route('admin.categories.create')"
        canExport="true"
        canImport="true"
        :exportRoute="route('admin.categories.export')"
        :templateRoute="route('admin.categories.template')" />


    <x-slide-over
        open="detailOpen"
        title="Kategori"
        isEditing="isEditing"
        onClose="closeDetail()"
        onToggleEdit="toggleEdit()"
        onConfirm="confirmEdit()"
        onCancel="cancelEdit()"
        onDelete="confirmDelete()"
        hasDelete="form.alat_count === 0">
        <!-- Category Hero Section -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 p-8 text-white">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium mb-3">
                        <x-heroicon-s-tag class="w-3 h-3" />
                        <span x-text="'ID: ' + (form.id || '#')"></span>
                    </div>
                    <h3 class="text-2xl font-bold mb-2" x-text="isEditing ? 'Ubah Kategori' : form.nama"></h3>
                </div>
                <div class="flex-shrink-0 w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border-4 border-white/30">
                    <x-heroicon-o-tag class="w-10 h-10 text-white/80" />
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 gap-3">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20 text-center">
                    <div class="text-xs text-indigo-100 mb-1">Total Alat Terkait</div>
                    <div class="text-2xl font-bold" x-text="form.alat_count || 0"></div>
                </div>
            </div>
        </div>

        <!-- Form Sections -->
        <div class="p-6 space-y-6">
            <form x-ref="editForm" :action="'/admin/categories/' + form.id" method="POST">
                @csrf
                @method('PUT')
                <!-- Detail Card -->
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <x-heroicon-o-identification class="w-5 h-5 text-indigo-600" />
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">Informasi Kategori</h4>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Nama Kategori</label>
                            <input type="text" name="nama" x-model="form.nama" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                    </div>
                </div>
            </form>

            <!-- Hidden Delete Form -->
            <form x-ref="deleteForm" :action="'/admin/categories/' + form.id" method="POST" class="hidden">
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
                <form action="{{ route('admin.categories.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Import Kategori</h3>
                            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <x-heroicon-o-x-mark class="w-6 h-6" />
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                <p class="text-xs text-indigo-700 leading-relaxed">
                                    Unggah file Excel (.xlsx atau .csv). Pastikan format kolom sesuai dengan template yang tersedia.
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