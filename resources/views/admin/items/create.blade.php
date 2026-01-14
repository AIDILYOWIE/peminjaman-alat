@extends('layouts.app')

@section('header', 'Tambah Alat Baru')

@section('content')
<div class="max-w-4xl mx-auto">
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
        <form action="#" method="POST" class="p-6 space-y-8">
            <!-- Section 1: Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2 md:col-span-2">
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

                    <div class="col-span-2 md:col-span-1">
                        <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengembalian</label>
                        <input type="date" name="return_date" id="return_date" class="block w-full border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border" placeholder="1">
                    </div>
                </div>
            </div>

            <!-- Section 3: Image -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2 mb-4">Foto Alat</h3>
                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <x-heroicon-o-photo class="mx-auto h-12 w-12 text-gray-400" />
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