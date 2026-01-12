@extends('layouts.app')

@section('header', 'Persetujuan Peminjaman')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu Persetujuan</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">5</h3>
                </div>
                <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Disetujui Hr Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">12</h3>
                </div>
                 <div class="p-3 bg-green-50 rounded-xl text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
         <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Ditolak Hr Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">1</h3>
                </div>
                 <div class="p-3 bg-red-50 rounded-xl text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Permintaan Masuk</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 bg-opacity-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Barang</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keperluan</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <!-- Request 1 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs ring-2 ring-white">AS</div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900">Arif Satrio</div>
                                    <div class="text-xs text-gray-500">Siswa - XII MM 2</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <ul class="list-disc list-inside text-sm text-gray-600">
                                <li>Sony Alpha a7 III <span class="text-xs text-gray-400">(1 Unit)</span></li>
                                <li>Lensa 50mm <span class="text-xs text-gray-400">(1 Unit)</span></li>
                            </ul>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">12 Jan - 14 Jan</div>
                            <div class="text-xs text-gray-500">2 Hari</div>
                        </td>
                         <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded-md">Tugas Akhir Sekolah</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Tolak">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <button class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                                    Setujui
                                </button>
                            </div>
                        </td>
                    </tr>

                     <!-- Request 2 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-full bg-pink-100 flex items-center justify-center text-pink-700 font-bold text-xs ring-2 ring-white">DP</div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900">Dewi Putri</div>
                                    <div class="text-xs text-gray-500">Siswa - XI BC 1</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <ul class="list-disc list-inside text-sm text-gray-600">
                                <li>Tripod Manfrotto <span class="text-xs text-gray-400">(1 Unit)</span></li>
                            </ul>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">12 Jan</div>
                            <div class="text-xs text-gray-500">1 Hari</div>
                        </td>
                         <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded-md">Praktik Lapangan</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Tolak">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <button class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                                    Setujui
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
