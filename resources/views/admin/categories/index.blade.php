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
        hasExport="true"
        :loading="true"
        onRowClick="openDetail($row)"
        addButtonText="Tambah"
        :addButtonRoute="route('admin.categories.create')"
        :exportRoute="route('admin.categories.export')" />


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
</div>
@endsection