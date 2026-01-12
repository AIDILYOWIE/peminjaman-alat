@extends('layouts.app')

@section('header', 'Tambah Alat Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('admin.items.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Data Alat</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah Baru</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="#" method="POST" class="p-6 space-y-8">
            <!-- Section 1: Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2 md:col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Alat</label>
                        <input type="text" name="name" id="name" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border" placeholder="Contoh: Sony Alpha a7 III">
                    </div>
                    
                     <div class="col-span-2 md:col-span-1">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Alat</label>
                        <input type="text" name="code" id="code" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border" placeholder="Contoh: CAM-001">
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="category" name="category" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border">
                            <option>Pilih Kategori</option>
                            <option>Kamera</option>
                            <option>Lensa</option>
                            <option>Audio</option>
                            <option>Lighting</option>
                            <option>Tripod & Monopod</option>
                        </select>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                         <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok</label>
                        <input type="number" name="stock" id="stock" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border" placeholder="1">
                    </div>
                </div>
            </div>

            <!-- Section 2: Details & Location -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">Detail & Lokasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi & Spesifikasi</label>
                        <textarea id="description" name="description" rows="4" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 p-4 bg-gray-50 border" placeholder="Tuliskan detail spesifikasi alat di sini..."></textarea>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label for="condition" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Awal</label>
                        <select id="condition" name="condition" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border">
                            <option value="good">Baik</option>
                            <option value="maintenance">Perlu Perbaikan</option>
                            <option value="damaged">Rusak</option>
                        </select>
                    </div>

                     <div class="col-span-2 md:col-span-1">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Penyimpanan</label>
                         <input type="text" name="location" id="location" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border" placeholder="Contoh: Lemari A - Rak 2">
                    </div>
                </div>
            </div>

            <!-- Section 3: Image -->
             <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">Foto Alat</h3>
                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload file</span>
                                <input id="file-upload" name="file-upload" type="file" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, GIF up to 5MB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.items.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors focus:ring-4 focus:ring-indigo-100">
                    Simpan Alat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
