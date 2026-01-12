@extends('layouts.app')

@section('header', 'Verifikasi Pengembalian')

@section('content')
<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-3 border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Scan kode QR peminjaman atau cari nama peminjam...">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Active Loans List -->
        <div class="lg:col-span-2 space-y-4">
             <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider px-1">Sedang Dipinjam</h3>
            
            <!-- Loan Card 1 (Late) -->
            <div class="bg-white rounded-xl border border-red-200 cursor-pointer hover:shadow-md transition-shadow relative overflow-hidden group">
                 <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                 <div class="absolute top-4 right-4 text-red-600 font-bold text-xs bg-white px-2 py-1 rounded-md shadow-sm border border-red-100 z-10">
                    Terlambat 1 Hari
                </div>
                
                <div class="p-6 relative z-0">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-lg font-bold text-gray-600">
                                BS
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Budi Santoso</h4>
                                <p class="text-sm text-gray-500 mb-2">Dipinjam: 10 Jan 2024</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        Lensa Canon 50mm
                                    </span>
                                </div>
                            </div>
                        </div>
                         <button class="px-4 py-2 border border-indigo-600 text-indigo-600 text-sm font-medium rounded-lg hover:bg-indigo-50 transition-colors">
                            Proses Pengembalian
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loan Card 2 (On Time) -->
            <div class="bg-white rounded-xl border border-gray-200 cursor-pointer hover:shadow-md transition-shadow relative">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start gap-4">
                             <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-lg font-bold text-gray-600">
                                SA
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Siti Aminah</h4>
                                <p class="text-sm text-gray-500 mb-2">Dipinjam: Hari ini, 09:00</p>
                                <div class="flex flex-wrap gap-2">
                                     <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        Kamera Nikon D3500
                                    </span>
                                     <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        Mic Wireless
                                    </span>
                                </div>
                            </div>
                        </div>
                         <button class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Proses Pengembalian
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Processor (Sidebar) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 h-fit sticky top-24">
            <h3 class="font-bold text-gray-900 mb-4 pb-4 border-b border-gray-100">Rincian Pengembalian</h3>
            
            <!-- Info Section -->
            <div class="space-y-4 mb-6">
                 <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase">Peminjam</span>
                    <span class="block text-sm font-bold text-gray-900">Budi Santoso</span>
                </div>
                <div>
                     <span class="block text-xs font-medium text-gray-500 uppercase">Item dikembalikan</span>
                     <ul class="mt-1 space-y-1">
                         <li class="flex items-center justify-between text-sm text-gray-700 bg-gray-50 px-2 py-1.5 rounded-md">
                             <span>Lensa Canon 50mm</span>
                             <select class="text-xs border-gray-200 rounded focus:ring-indigo-500 focus:border-indigo-500 ml-2">
                                 <option>Baik</option>
                                 <option>Rusak</option>
                                 <option>Hilang</option>
                             </select>
                         </li>
                     </ul>
                </div>
                 <div>
                    <span class="block text-xs font-medium text-gray-500 uppercase">Keterlambatan</span>
                    <span class="block text-sm font-bold text-red-600">1 Hari</span>
                </div>
            </div>

            <!-- Fine Calculation -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2 border border-gray-100">
                 <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Denda Terlambat</span>
                    <span class="font-medium">Rp 50.000</span>
                </div>
                 <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Denda Kerusakan</span>
                    <span class="font-medium text-gray-900">Rp 0</span>
                </div>
                 <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200 mt-2">
                    <span class="text-gray-900">Total Denda</span>
                    <span class="text-indigo-600">Rp 50.000</span>
                </div>
            </div>

            <button class="w-full py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                Konfirmasi Pengembalian
            </button>
             <button class="w-full mt-3 py-2.5 text-gray-500 font-medium hover:text-gray-700 text-sm">
                Batalkan
            </button>
        </div>
    </div>
</div>
@endsection
