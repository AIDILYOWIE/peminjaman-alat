@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Invoice Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-2xl overflow-hidden" id="invoice-print">
        <!-- Header -->
        <div class="p-8 bg-indigo-600 flex justify-between items-start text-white relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-lg">
                        <x-heroicon-s-beaker class="w-5 h-5 text-indigo-600" />
                    </div>
                    <span class="text-xl font-black tracking-tight">InventApp</span>
                </div>
                <h1 class="text-3xl font-black mb-1">Electronic Invoice</h1>
                <p class="text-indigo-100 text-sm font-medium opacity-90">ID Peminjaman: #{{ $borrowing->invoice_code }}</p>
            </div>
            <div class="relative z-10 text-right">
                <span class="inline-block px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-widest">
                    {{ strtoupper($borrowing->status) }}
                </span>
                <p class="mt-4 text-xs font-bold text-indigo-50">{{ $borrowing->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>

        <!-- Body -->
        <div class="p-8 space-y-8">
            <!-- User Detail -->
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Peminjam</h4>
                    <p class="font-black text-gray-900 text-lg">{{ $borrowing->peminjam->username }}</p>
                    <p class="text-sm text-gray-500 font-medium">Employee/Student ID: #{{ $borrowing->user_id }}</p>
                </div>
                <div class="text-right">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Petugas Penyetuju</h4>
                    <p class="font-black text-gray-900">{{ $borrowing->petugas ? $borrowing->petugas->username : 'MENUNGGU KONFIRMASI' }}</p>
                    <p class="text-sm text-gray-500 font-medium italic">{{ $borrowing->petugas ? 'Terverifikasi System' : '-' }}</p>
                </div>
            </div>

            <div class="h-px bg-gray-100"></div>

            <!-- Items Table -->
            <div>
                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Detail Alat</h4>
                <div class="space-y-4">
                    @foreach($borrowing->details as $detail)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl border border-gray-100 flex items-center justify-center text-indigo-600">
                                <x-heroicon-o-wrench-screwdriver class="w-6 h-6" />
                            </div>
                            <div>
                                <p class="font-black text-gray-900 text-sm">{{ $detail->alat->nama }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $detail->alat->code }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-indigo-600 text-sm">{{ $detail->jumlah }} Unit</p>
                            <p class="text-[10px] font-bold text-gray-400">Kondisi: Baik</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Timeline -->
            <div class="p-6 bg-indigo-50 rounded-3xl border border-indigo-100">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xs font-black text-indigo-900 uppercase tracking-wider">Timeline Peminjaman</h4>
                    <div class="flex items-center gap-1.5">
                        <x-heroicon-s-clock class="w-4 h-4 text-indigo-400" />
                        <span class="text-[10px] font-bold text-indigo-600 uppercase">Automated Reminder Active</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-8 relative">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase">Mulai Pinjam</p>
                        <p class="text-sm font-black text-indigo-900">{{ $borrowing->tgl_pinjam ? $borrowing->tgl_pinjam->format('d M Y') : 'Menunggu Admin' }}</p>
                    </div>
                    <div class="space-y-1 text-right">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase">Deadline Kembali</p>
                        <p class="text-sm font-black text-indigo-900">{{ $borrowing->tgl_pengembalian->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Footer / Disclaimer -->
            <div class="text-center pt-8">
                <div class="w-24 h-24 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center mx-auto mb-6 scale-75 md:scale-100 shadow-inner">
                    <!-- Placeholder QR Code using SVG or Icon -->
                    <x-heroicon-o-qr-code class="w-16 h-16 text-gray-400" />
                </div>
                <p class="text-[11px] text-gray-400 font-medium px-12 leading-relaxed italic">
                    Tunjukkan Electronic Invoice ini kepada petugas di ruang inventaris untuk pengambilan barang. <br>
                    Harap menjaga kondisi alat dengan baik sesuai standar operasional yang berlaku.
                </p>
            </div>
        </div>

        <!-- Footer Strip -->
        <div class="bg-gray-50 p-6 border-t border-gray-100 flex justify-between items-center">
            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">© 2026 INVENTAPP SYSTEM • AUTOMATED DOCUMENT</p>
            <button onclick="window.print()" class="px-6 py-2 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-100 transition-all flex items-center gap-2">
                <x-heroicon-o-printer class="w-4 h-4" />
                Cetak PDF
            </button>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 flex justify-center">
        <a href="{{ route('user.borrow.history') }}" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors group">
            <x-heroicon-o-arrow-left class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
            Kembali ke Riwayat
        </a>
    </div>
</div>

<style>
    @media print {
        body {
            background: white !important;
        }

        nav,
        footer,
        .back-button,
        button {
            display: none !important;
        }

        main {
            padding: 0 !important;
        }

        .max-w-3xl {
            max-width: 100% !important;
            margin: 0 !important;
        }

        .rounded-3xl {
            border-radius: 0 !important;
        }

        .shadow-2xl {
            box-shadow: none !important;
        }

        #invoice-print {
            border: none !important;
        }
    }
</style>
@endsection