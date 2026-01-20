@extends('layouts.app')

@section('header', 'Data Pengguna')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isEditing: false, 
    isLoading: true,
    isSubmitting: false,
    selectedUser: {},
    form: {
        id: null,
        username: '',
        no_induk: '',
        role: '',
        password: ''
    },
    init() {
        setTimeout(() => {
            this.isLoading = false;
        }, 1500);
    },
    openDetail(user) {
        this.selectedUser = user;
        this.form = { 
            ...user, 
            password: '' 
        };
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
        this.form = { ...this.selectedUser, password: '' };
        this.isEditing = false;
    },
    confirmEdit() {
        this.isSubmitting = true;
        this.$refs.editForm.submit();
    },
    confirmDelete() {
        this.$dispatch('open-confirm', {
            title: 'Hapus Pengguna',
            message: 'Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.',
            confirmText: 'Ya, Hapus',
            onConfirm: () => {
                this.isSubmitting = true;
                this.$refs.deleteForm.submit();
            }
        });
    },
    getRoleLabel(role) {
        const labels = {
            'admin': 'Admin',
            'petugas': 'Petugas',
            'peminjam': 'Peminjam'
        };
        return labels[role] || role;
    }
}">
    @php
    $columns = [
    [
    'label' => 'Username',
    'key' => 'username',
    'align' => 'text-left',
    ],
    [
    'label' => 'No. Induk / Identitas',
    'key' => 'no_induk',
    'align' => 'text-left',
    ],
    [
    'label' => 'Role',
    'key' => 'role',
    'component' => 'badge',
    'params' => ['color' => 'random'],
    'align' => 'text-center',
    'class' => 'w-px whitespace-nowrap'
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$users"
        paginated="true"
        searchPlaceholder="Cari pengguna..."
        addButtonText="Tambah"
        :addButtonRoute="route('admin.users.create')"
        hasFilter="true"
        hasExport="true"
        :loading="true"
        onRowClick="openDetail($row)"
        :exportRoute="route('admin.users.export')" />

    <x-slide-over
        open="detailOpen"
        title="Pengguna"
        isEditing="isEditing"
        onClose="closeDetail()"
        onToggleEdit="toggleEdit()"
        onConfirm="confirmEdit()"
        onCancel="cancelEdit()"
        onDelete="confirmDelete()">

        <!-- User Profile Hero -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 sm:p-8 p-4 text-white">
            <div class="flex items-center justify-between gap-4">

                <div>
                    <h3 class="text-xl font-bold" x-text="form.username"></h3>
                    <p class="text-indigo-100 text-sm" x-text="getRoleLabel(form.role)"></p>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border-2 border-white/30">
                    <x-heroicon-o-user class="w-8 h-8 text-white" />
                </div>
            </div>
        </div>

        <!-- Form Details -->
        <div class="sm:p-6 p-4">
            <form x-ref="editForm" :action="'{{ route('admin.users.index') }}/' + form.id" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 space-y-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <x-heroicon-o-identification class="w-5 h-5 text-indigo-600" />
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">Informasi Akun</h4>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Username</label>
                            <input type="text" name="username" x-model="form.username" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                            @error('username') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-semibold text-gray-500 mb-2">No. Induk (NIS/NIP)</label>
                            <input type="text" name="no_induk" x-model="form.no_induk" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                            @error('no_induk') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Role</label>
                            <select name="role" x-model="form.role" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0 disabled:appearance-none">
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                                <option value="peminjam">Peminjam</option>
                            </select>
                            @error('role') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div x-show="isEditing" class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Ganti Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" x-model="form.password"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 transition-all">
                            @error('password') <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>



                </div>
            </form>

            <form x-ref="deleteForm" :action="'{{ route('admin.users.index') }}/' + form.id" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </x-slide-over>
</div>
@endsection