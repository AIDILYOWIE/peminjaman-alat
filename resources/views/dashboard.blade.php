@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Alat</p>
                <h3 class="text-2xl font-bold text-gray-900">2,543</h3>
            </div>
            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                +12%
            </span>
            <span class="text-gray-400 ml-2">dari bulan lalu</span>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Sedang Dipinjam</p>
                <h3 class="text-2xl font-bold text-gray-900">45</h3>
            </div>
            <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-gray-400">Sedang aktif digunakan</span>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Terlambat</p>
                <h3 class="text-2xl font-bold text-gray-900">3</h3>
            </div>
            <div class="p-2 bg-red-50 rounded-lg text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-red-600 font-medium">Perlu tindakan segera</span>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Pengembalian Hari Ini</p>
                <h3 class="text-2xl font-bold text-gray-900">12</h3>
            </div>
            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">Lihat jadwal &rarr;</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activity / Items Table -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h2>
            <button class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Lihat Semua</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Alat</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $activities = [
                            ['user' => 'Arif Satrio', 'item' => 'Sony Alpha a7 III', 'status' => 'Dipinjam', 'color' => 'blue', 'date' => '2 jam lalu'],
                            ['user' => 'Sarah Wijaya', 'item' => 'Tripod Manfrotto', 'status' => 'Dikembalikan', 'color' => 'green', 'date' => '5 jam lalu'],
                            ['user' => 'Budi Santoso', 'item' => 'Lensa Canon Visual 50mm', 'status' => 'Terlambat', 'color' => 'red', 'date' => '1 hari lalu'],
                            ['user' => 'Dian Sastro', 'item' => 'Zoom H6 Recorder', 'status' => 'Menunggu', 'color' => 'yellow', 'date' => 'Baru saja'],
                        ]
                    @endphp
                    @foreach($activities as $activity)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                {{ substr($activity['user'], 0, 1) }}
                            </div>
                            {{ $activity['user'] }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $activity['item'] }}</td>
                        <td class="px-6 py-4">
                            @if($activity['status'] == 'Dipinjam')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Dipinjam</span>
                            @elseif($activity['status'] == 'Dikembalikan')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Dikembalikan</span>
                            @elseif($activity['status'] == 'Terlambat')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Terlambat</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $activity['date'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions / Notifications -->
    <div class="space-y-6">
        <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-white opacity-10 rounded-full"></div>
            
            <h3 class="text-lg font-bold mb-2">Ajukan Peminjaman</h3>
            <p class="text-indigo-100 text-sm mb-6">Butuh alat untuk proyek? Cek ketersediaan dan ajukan sekarang.</p>
            <a href="{{ route('user.borrow.index') }}" class="inline-block w-full text-center bg-white text-indigo-600 font-semibold py-2.5 rounded-lg hover:bg-indigo-50 transition-colors shadow-sm">
                Lihat Katalog
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Pengingat</h3>
            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Batas Waktu Pengembalian</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Lensa Canon 50mm harus dikembalikan hari ini sebelum 16:00.</p>
                    </div>
                </div>
                <div class="w-full h-px bg-gray-50"></div>
                <div class="flex items-start gap-4">
                     <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Stok Menipis</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Kamera Sony Alpha tersisa 1 unit.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
