@extends('layouts.app')

@section('header', 'Tambah Pengguna')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ 
    isLoading: true,
    isSubmitting: false,
    username: '',
    role: 'peminjam',
    init() {
        setTimeout(() => { this.isLoading = false }, 1500);
    }
}">
    <!-- Breadcrumb -->
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <x-heroicon-m-home class="w-4 h-4 mr-2" />
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <x-heroicon-m-chevron-right class="w-5 h-5 text-gray-400" />
                    <a href="{{ route('admin.users.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Data Pengguna</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <x-heroicon-m-chevron-right class="w-5 h-5 text-gray-400" />
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah Baru</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden min-h-[400px]">
        <!-- Skeleton Loading -->
        <div x-show="isLoading" class="p-6 space-y-8 animate-pulse">
            <div>
                <div class="h-6 bg-gray-200 rounded w-1/4 mb-6"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2 md:col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6 mb-2"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6 mb-2"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6 mb-2"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6 mb-2"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <div class="h-10 bg-gray-100 rounded w-24"></div>
                <div class="h-10 bg-gray-100 rounded w-32"></div>
            </div>
        </div>

        <!-- Real Form -->
        <form x-show="!isLoading" x-cloak action="{{ route('admin.users.store') }}" method="POST" @submit="isSubmitting = true" class="p-6 sm:p-8 space-y-8">
            @csrf

            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-6">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Username -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('username') border-red-500 @enderror"
                            placeholder="Contoh: ahmad_siswa">
                        @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- No Induk -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="no_induk" class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk (NIS/NIP)</label>
                        <input type="text" name="no_induk" id="no_induk" value="{{ old('no_induk') }}" required
                            class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('no_induk') border-red-500 @enderror"
                            placeholder="Masukkan NIS atau NIP">
                        @error('no_induk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Role Selector -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role Akses</label>
                        <select name="role" id="role" required
                            class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('role') border-red-500 @enderror">
                            <option value="peminjam" {{ old('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    :disabled="isSubmitting"
                    class="relative inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors focus:ring-4 focus:ring-indigo-100 disabled:opacity-70 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">Simpan</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection