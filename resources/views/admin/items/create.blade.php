@extends('layouts.app')

@section('header', 'Tambah Alat')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ 
    isLoading: true, 
    isSubmitting: false,
    nama: '{{ old('nama', '') }}',
    stock: {{ old('stock', 0) }}, 
    code: '{{ old('code', '') }}',
    imageFile: null,
    imageUrl: null,
    isDragging: false,
    init() {
        setTimeout(() => { this.isLoading = false }, 1500);
        this.$watch('nama', () => this.generateCode());
    },
    generateCode() {
        if (this.nama.length < 3) {
            this.code = '';
            return;
        }

        // Get category name from selected option
        let catSelect = document.getElementById('kategori_id');
        let catName = catSelect.options[catSelect.selectedIndex]?.text || 'ALAT';
        
        if (catName === 'Pilih Kategori') catName = 'ALAT';

        let catPrefix = catName.substring(0, 3).toUpperCase();
        
        // Smart Name Logic for Preview
        let words = this.nama.trim().split(/\s+/);
        let namePrefix = '';
        
        if (words.length >= 2) {
            let firstPart = words[0].substring(0, 2).toUpperCase();
            let lastPart = words[words.length - 1].substring(0, 3).toUpperCase();
            namePrefix = firstPart + lastPart;
        } else {
            namePrefix = this.nama.substring(0, 5).toUpperCase();
        }
        
        this.code = catPrefix + '-' + namePrefix;
    },
    handleFileSelect(e) {
        const files = e.target.files || e.dataTransfer.files;
        if (files.length > 0) {
            this.imageFile = files[0];
            this.imageUrl = URL.createObjectURL(this.imageFile);
        }
    },
    removeFile() {
        this.imageFile = null;
        if (this.imageUrl) {
            URL.revokeObjectURL(this.imageUrl);
            this.imageUrl = null;
        }
        this.$refs.gambar.value = '';
    },
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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
                    <a href="{{ route('admin.items.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Data Alat</a>
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
                <div class="h-6 bg-gray-200 rounded w-1/4 mb-4"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <div class="h-4 bg-gray-100 rounded w-1/6"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="h-4 bg-gray-100 rounded w-1/6"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                    <div class="col-span-2">
                        <div class="h-4 bg-gray-100 rounded w-1/6"></div>
                        <div class="h-10 bg-gray-100 rounded w-full"></div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <div class="h-10 bg-gray-100 rounded w-24"></div>
                <div class="h-15 bg-gray-100 rounded w-24"></div>
            </div>
        </div>
        <form x-show="!isLoading" x-cloak action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8" @submit="isSubmitting = true">
            @csrf
            <!-- Section 1: Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Alat</label>
                        <input type="text" name="nama" id="nama" x-model="nama" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('nama') border-red-500 @enderror" placeholder="Contoh: Sony Alpha a7 III">
                        @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">SKU / Model Code</label>
                        <input type="text" name="code" id="code" x-model="code"
                            class="block w-full border-gray-200 rounded-lg text-sm px-4 py-2.5 bg-gray-50 border @error('code') border-red-500 @enderror focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Opsional (Otomatis: KAT-NAMA)">
                        @error('code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="kategori_id" name="kategori_id" @change="generateCode()" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('kategori_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('kategori_id') == $category->id ? 'selected' : '' }}>{{ $category->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Total Stok</label>
                        <input type="number" name="stock" id="stock" x-model="stock" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('stock') border-red-500 @enderror" placeholder="1">
                        @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label for="denda" class="block text-sm font-medium text-gray-700 mb-1">Denda per Hari</label>
                        <input type="number" name="denda" id="denda" value="{{ old('denda', 0) }}" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('denda') border-red-500 @enderror" placeholder="5000">
                        @error('denda') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border @error('deskripsi') border-red-500 @enderror" placeholder="Masukkan deskripsi lengkap alat...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Section 3: Image -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Alat</label>

                        <div
                            class="relative flex flex-col items-center justify-center px-6 py-10 border-2 border-dashed rounded-2xl transition-all duration-200 group"
                            :class="isDragging ? 'border-indigo-500 bg-indigo-50/50' : (imageFile ? 'border-green-500 bg-green-50/10' : 'border-gray-200 hover:border-indigo-400 hover:bg-gray-50')"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="isDragging = false; handleFileSelect($event)">
                            <input id="gambar" name="gambar" type="file" class="sr-only" x-ref="gambar" @change="handleFileSelect($event)" accept="image/*">

                            <template x-if="!imageFile">
                                <div class="space-y-4 text-center">
                                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                                        <x-heroicon-o-photo class="w-8 h-8 text-gray-400 group-hover:text-indigo-500" />
                                    </div>
                                    <div class="space-y-1">
                                        <label for="gambar" class="relative cursor-pointer text-indigo-600 font-semibold hover:text-indigo-500">
                                            <span>Upload file</span>
                                        </label>
                                        <p class="text-xs text-gray-500">atau drag and drop</p>
                                    </div>
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">PNG, JPG, GIF max 5MB</p>
                                </div>
                            </template>

                            <template x-if="imageFile">
                                <div class="w-full flex flex-col items-center gap-4 p-2">
                                    <button type="button" @click="removeFile()" class="absolute cursor-pointer top-4 right-4 group/btn transition-all duration-200 z-10">
                                        <x-heroicon-m-x-mark class="w-5 h-5 text-gray-400 group-hover/btn:text-red-500" />
                                    </button>

                                    <div class="relative w-24 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-100 shadow-sm">
                                        <template x-if="imageUrl">
                                            <img :src="imageUrl" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!imageUrl">
                                            <div class="w-full h-full flex items-center justify-center">
                                                <x-heroicon-o-photo class="w-8 h-8 text-gray-400" />
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0 text-center">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="imageFile.name"></p>
                                        <p class="text-xs text-gray-500 font-medium" x-text="formatFileSize(imageFile.size)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @error('gambar') <p class="mt-2 text-xs text-red-500 flex items-center gap-1"><x-heroicon-m-exclamation-circle class="w-4 h-4" /> {{ $message }}</p> @enderror
                    </div>

                </div>
            </div>




            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.items.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    :disabled="isSubmitting"
                    class="cursor-pointer relative inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors focus:ring-4 focus:ring-indigo-100 disabled:opacity-70 disabled:cursor-not-allowed">
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