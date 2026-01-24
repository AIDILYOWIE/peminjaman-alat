@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Daftar Peminjaman</h1>
            <p class="text-sm text-gray-500 font-medium">Pantau status pendaftaran dan riwayat peminjaman alat Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <select class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-500 shadow-sm">
                <option>Semua Status</option>
                <option>Pending</option>
                <option>Dipinjam</option>
                <option>Selesai</option>
            </select>
        </div>
    </div>

    @if($borrowings->count() > 0)
    <div class="space-y-6">
        @foreach($borrowings as $borrowing)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <!-- Header Card -->
            <div class="px-6 py-4 border-b border-gray-50 flex flex-wrap items-center justify-between gap-4 bg-gray-50/50">
                <div class="flex items-center gap-4">
                    <x-heroicon-o-shopping-bag class="w-5 h-5 text-gray-400" />
                    <div class="text-[11px] font-bold text-gray-500 uppercase tracking-widest leading-none">
                        {{ $borrowing->created_at->format('d M Y') }}
                    </div>
                </div>
                <!-- Status Badge -->
                @php
                $statusStyles = [
                'pending' => 'bg-orange-50 text-orange-600 border-orange-100',
                'dipinjam' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                'selesai' => 'bg-blue-50 text-blue-600 border-blue-100',
                'ditolak' => 'bg-red-50 text-red-600 border-red-100',
                ];
                $statusLabels = [
                'pending' => 'Menunggu Persetujuan',
                'dipinjam' => 'Sedang Dipinjam',
                'selesai' => 'Selesai Dikembalikan',
                'ditolak' => 'Ditolak',
                ];
                @endphp
                <span class="px-3 py-1 rounded-lg border {{ $statusStyles[$borrowing->status] }} text-[10px] font-black uppercase tracking-wider">
                    {{ $statusLabels[$borrowing->status] }}
                </span>
            </div>

            <!-- Content Card -->
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Tools List Preview -->
                    <div class="flex-1 space-y-4">
                        @foreach($borrowing->details as $detail)
                        <div class="flex gap-4 items-center">
                            <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 overflow-hidden flex-shrink-0">
                                <img src="{{ asset('storage/' . ($detail->alat->gambar ?? 'default.png')) }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $detail->alat->nama ?? 'Alat Dihapus' }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $detail->alat->code ?? 'N/A' }} • {{ $detail->jumlah }} Unit</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Side Stats -->
                    <div class="md:w-64 md:border-l border-gray-100 md:pl-6 space-y-4">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Batas Kembali</p>
                            <p class="text-sm font-black text-gray-900">{{ $borrowing->tgl_pengembalian->format('d M Y') }}</p>
                        </div>

                        @if($borrowing->denda > 0)
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Denda</p>
                            <p class="text-sm font-black text-red-600">Rp {{ number_format($borrowing->denda, 0, ',', '.') }}</p>
                        </div>
                        @endif

                        @if($borrowing->status !== 'ditolak')
                        <div class="pt-2">
                            <a href="{{ route('user.borrow.invoice', $borrowing->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-indigo-500 text-indigo-600 text-xs font-black rounded-xl hover:bg-indigo-50 transition-all">
                                <x-heroicon-o-receipt-percent class="w-4 h-4" />
                                Lihat Invoice
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="mt-8">
            {{ $borrowings->links() }}
        </div>
    </div>
    @else
    <div class="bg-white rounded-3xl border border-gray-100 py-32 flex flex-col items-center justify-center text-center px-6">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
            <x-heroicon-o-clipboard-document-list class="w-12 h-12 text-gray-300" />
        </div>
        <h3 class="text-xl font-black text-gray-900 mb-2">Belum Memiliki Riwayat</h3>
        <p class="text-gray-500 text-sm max-w-xs mx-auto mb-8">Anda belum pernah melakukan peminjaman alat apapun. Ayo mulai meminjam pertama kali!</p>
        <a href="{{ route('user.borrow.index') }}" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-100">Pinjam Alat Sekarang</a>
    </div>
    @endif
</div>
@endsection