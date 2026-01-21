@extends('layouts.app')

@section('header', 'Peminjaman Alat')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 h-full">
    <!-- Main Catalog Area -->
    <div class="flex-1">
        <!-- Search & Filter -->
        <div class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400" />
                </div>
                <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="Cari alat...">
            </div>
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                <button class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg whitespace-nowrap">Semua</button>
                <button class="px-4 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 text-sm font-medium rounded-lg whitespace-nowrap transition-colors">Kamera</button>
                <button class="px-4 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 text-sm font-medium rounded-lg whitespace-nowrap transition-colors">Lensa</button>
                <button class="px-4 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 text-sm font-medium rounded-lg whitespace-nowrap transition-colors">Aksesoris</button>
            </div>
        </div>

        <!-- Catalog Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Item Card 1 -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 relative">
                    <!-- Placeholder Image -->
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400 bg-gray-100">
                        <x-heroicon-o-photo class="w-12 h-12" />
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">Kamera</span>
                        <span class="flex items-center text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-md">
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></div>
                            Tersedia (4)
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">Sony Alpha a7 III</h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">Mirrorless full-frame camera dengan kemampuan low-light yang sangat baik.</p>

                    <button class="w-full py-2.5 flex items-center justify-center gap-2 border border-indigo-600 text-indigo-600 font-medium rounded-xl hover:bg-indigo-600 hover:text-white transition-all active:scale-95">
                        <x-heroicon-o-plus class="w-5 h-5" />
                        Pinjam Alat
                    </button>
                    <!-- Button State: Added -->
                    <!-- <button class="w-full py-2.5 flex items-center justify-center gap-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all">
                        <x-heroicon-o-check class="w-5 h-5" />
                        Ditambahkan
                    </button> -->
                </div>
            </div>

            <!-- Item Card 2 -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 relative">
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400 bg-gray-100">
                        <x-heroicon-o-video-camera class="w-12 h-12" />
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Aksesoris</span>
                        <span class="flex items-center text-xs text-red-600 font-medium bg-red-50 px-2 py-1 rounded-md">
                            <div class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></div>
                            Habis
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">Tripod Manfrotto</h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">Tripod kokoh untuk kamera video dan foto.</p>

                    <button disabled class="w-full py-2.5 flex items-center justify-center gap-2 border border-gray-200 text-gray-400 font-medium rounded-xl cursor-not-allowed bg-gray-50">
                        Stok Habis
                    </button>
                </div>
            </div>

            <!-- Item Card 3 -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 relative">
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400 bg-gray-100">
                        <x-heroicon-o-microphone class="w-12 h-12" />
                    </div>
                    <!-- Label: Maintenance -->
                    <div class="absolute top-2 right-2 px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded-md shadow-sm">
                        MAINTENANCE
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700">Audio</span>
                        <span class="flex items-center text-xs text-yellow-600 font-medium bg-yellow-50 px-2 py-1 rounded-md">
                            <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></div>
                            Perbaikan
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">Zoom H6 Recorder</h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">Portable recorder 6-track.</p>

                    <button disabled class="w-full py-2.5 flex items-center justify-center gap-2 border border-gray-200 text-gray-400 font-medium rounded-xl cursor-not-allowed bg-gray-50">
                        Tidak Tersedia
                    </button>
                </div>
            </div>
            <!-- Repeat Item 1 for grid demo -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 relative">
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400 bg-gray-100">
                        <x-heroicon-o-camera class="w-12 h-12" />
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">Kamera</span>
                        <span class="flex items-center text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded-md">
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></div>
                            Tersedia (1)
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">Canon EOS 5D Mark IV</h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">DSLR profesional andalan fotografer.</p>

                    <button class="w-full py-2.5 flex items-center justify-center gap-2 border border-indigo-600 text-indigo-600 font-medium rounded-xl hover:bg-indigo-600 hover:text-white transition-all active:scale-95">
                        <x-heroicon-o-plus class="w-5 h-5" />
                        Pinjam Alat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Cart Summary (Desktop) -->
    <div class="hidden xl:block w-80 flex-shrink-0">
        <div class="sticky top-24 bg-white rounded-2xl shadow-lg border border-indigo-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <x-heroicon-o-shopping-bag class="w-5 h-5 text-indigo-600" />
                Item Dipilih
                <span class="bg-indigo-600 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-auto">0</span>
            </h3>

            <!-- Empty State -->
            <div class="text-center py-8">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                    <x-heroicon-o-shopping-cart class="w-8 h-8 text-gray-300" />
                </div>
                <p class="text-gray-500 text-sm">Belum ada alat yang dipilih.</p>
                <p class="text-gray-400 text-xs mt-1">Pilih alat dari katalog untuk mulai meminjam.</p>
            </div>

            <!-- List State (Hidden for Empty) -->
            <!-- 
            <ul class="space-y-3 mb-6">
                <li class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex-shrink-0"></div>
                        <div>
                            <p class="font-medium text-gray-800">Sony Alpha a7</p>
                            <p class="text-xs text-gray-500">1 Unit</p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-red-500"><x-heroicon-o-x-mark class="w-4 h-4" /></button>
                </li>
            </ul>
            
            <div class="border-t border-gray-100 pt-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Durasi</span>
                    <span class="font-medium text-gray-900">1 Hari</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total Item</span>
                    <span class="font-medium text-gray-900">1</span>
                </div>
            </div>

            <button class="w-full mt-6 bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                Ajukan Peminjaman
            </button> 
            -->
        </div>
    </div>
</div>
@endsection