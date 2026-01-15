@extends('layouts.app')

@section('header', 'Data Kategori')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isEditing: false, 
    selectedUser: {},
    form: {
        name: '',
        member: 0,
    },
    openDetail(user) {
        this.selectedUser = user;
        this.form = { ...user };
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
        this.form = { ...this.selectedUser };
        this.isEditing = false;
    },
    confirmEdit() {
        this.selectedUser = { ...this.form };
        this.isEditing = false;
    }
}">
    @php
    $columns = [
    [
    'label' => 'Nama',
    'key' => 'name',
    'class' => 'w-full font-semibold'
    ],
    [
    'label' => 'Jumlah Alat',
    'key' => 'member',
    'class' => 'w-full sm:min-w-[200px] xl:min-w-[500px] min-w-[150px]'
    ],
    ];

    $users = [
    [
    'id' => 1,
    'name' => 'CPU',
    'member' => 50 
    ],
    [
    'id' => 2,
    'name' => 'Mouse',
    'member' => 20
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$users"
        paginated="true"
        searchPlaceholder="Cari pengguna berdasarkan nama..."
        hasFilter="true"
        hasExport="true"
        onRowClick="openDetail($row)"
        addButtonText="Tambah"
        :addButtonRoute="route('admin.categories.create')" />


    <x-slide-over
        open="detailOpen"
        title="Pengguna"
        isEditing="isEditing"
        onClose="closeDetail()"
        onToggleEdit="toggleEdit()"
        onConfirm="confirmEdit()"
        onCancel="cancelEdit()">
        <!-- User Hero Section -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 p-8 text-white">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium mb-3">
                        <x-heroicon-s-user class="w-3 h-3" />
                        <span x-text="form.no_induk"></span>
                    </div>
                    <h3 class="text-2xl font-bold mb-2" x-text="isEditing ? 'Edit Profil' : form.name"></h3>
                    <p class="text-indigo-100 text-sm" x-text="form.email"></p>
                </div>
                <div class="flex-shrink-0 w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-4 border-white/30">
                    <span class="text-2xl font-bold" x-text="form.name ? form.name.split(' ').map(n => n[0]).join('').toUpperCase() : ''"></span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Role Akun</div>
                    <div class="text-sm font-bold" x-text="form.role"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Terdaftar Sejak</div>
                    <div class="text-sm font-bold" x-text="form.joined"></div>
                </div>
            </div>
        </div>

        <!-- Form Sections -->
        <div class="p-6 space-y-6">
            <!-- Account Info Card -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-identification class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Informasi Akun</h4>
                </div>

                <div class="space-y-4">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">No Induk</label>
                            <input type="text" x-model="form.no_induk" :disabled="true"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                            <input type="text" x-model="form.name" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Alamat Email</label>
                            <input type="email" x-model="form.email" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Role</label>
                            <!-- Mode View: Badge -->
                            <div x-show="!isEditing" class="py-1">
                                <template x-if="form.role === 'Peminjam'">
                                    <x-badge color="green" value="Peminjam" />
                                </template>
                                <template x-if="form.role === 'Admin'">
                                    <x-badge color="red" value="Admin" />
                                </template>
                                <template x-if="form.role === 'Petugas'">
                                    <x-badge color="yellow" value="Petugas" />
                                </template>
                            </div>

                            <select x-show="isEditing" x-model="form.role" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:appearance-none disabled:px-0">
                                <option>Admin</option>
                                <option>Petugas</option>
                                <option>Peminjam</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Card -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-shield-check class="w-5 h-5 text-red-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Keamanan</h4>
                </div>

                <button class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                    <x-heroicon-o-key class="w-4 h-4" />
                    Reset Password
                </button>
            </div>
        </div>
    </x-slide-over>
</div>
@endsection