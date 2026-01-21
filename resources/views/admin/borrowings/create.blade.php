@extends('layouts.app')

@section('header', 'Tambah Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ 
    isLoading: true, 
    isSubmitting: false,
    init() {
        setTimeout(() => { this.isLoading = false }, 1500);
    }
}">
    {{-- Breadcrumb (Matched exactly with project standard) --}}
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
                    <a href="{{ route('admin.borrowings.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Data Peminjaman</a>
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

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Skeleton Loading -->
        <div x-show="isLoading" class="p-6 space-y-8 animate-pulse">
            <div>
                <div class="h-6 bg-gray-200 rounded w-1/4 mb-6"></div>
                <div class="grid grid-cols-1 gap-6">
                    <div class="col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6 mb-2"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6 mb-2"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                </div>
            </div>
            <!-- Section 2: Daftar Alat Skeleton -->
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                    <div class="h-8 bg-indigo-50 rounded-lg w-28"></div>
                </div>
                <div class="grid grid-cols-1 gap-3 p-4 bg-gray-50/50 rounded-xl border border-gray-100">
                    <div class="col-span-1 md:col-span-8">
                        <div class="h-3 bg-gray-100 rounded w-20 mb-2"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-1 md:col-span-3">
                        <div class="h-3 bg-gray-100 rounded w-12 mb-2 mx-auto"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                </div>
            </div>

            <!-- Action Skeleton -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <div class="h-10 bg-gray-100 rounded w-24"></div>
                <div class="h-10 bg-gray-100 rounded w-32"></div>
            </div>
        </div>

        <form x-show="!isLoading" x-cloak action="{{ route('admin.borrowings.store') }}" method="POST" @submit="isSubmitting = true" class="p-6 space-y-8">
            @csrf

            {{-- Section 1: Informasi Dasar --}}
            <div>
                <h3 class=" text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <x-input.select
                            label="Peminjam"
                            name="user_id"
                            required
                            placeholder="Cari user (Siswa/Guru)..."
                            :options="$users->pluck('username', 'id')" />
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <x-input.date
                            label="Tanggal Pengembalian"
                            name="return_date"
                            required
                            description="Tentukan kapan alat harus dikembalikan." />
                    </div>
                </div>
            </div>

            {{-- Section 2: Daftar Alat --}}
            <div>
                <x-input.tool-list
                    label="Daftar Alat yang Akan Dipinjam"
                    :tools="$items->pluck('nama', 'id')" />
            </div>

            {{-- Form Actions (Inside the card, border-t) --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.borrowings.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    :disabled="isSubmitting"
                    class="relative inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors focus:ring-4 focus:ring-indigo-100 disabled:opacity-70 disabled:cursor-not-allowed">
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