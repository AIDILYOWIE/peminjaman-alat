@extends('layouts.user')

@php
$filename = 'Invoice-Kembali-' . str_replace(' ', '', $borrowing->peminjam->username) . '-' . now()->format('dmY');

// Calculate late days
$deadline = $borrowing->tgl_pengembalian->startOfDay();
$returnedAt = $borrowing->updated_at->startOfDay();
$lateDays = $returnedAt->greaterThan($deadline) ? $returnedAt->diffInDays($deadline) : 0;
@endphp

@section('title', $filename)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Main Invoice Card -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden" id="invoice-card">
        <!-- Top Bar (Web Only) -->
        <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center no-print">
            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Preview Invoice Pengembalian</h2>
            <div class="flex items-center gap-3">
                <button onclick="prepareAndPrint()" class="px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2 shadow-lg shadow-indigo-100 active:scale-95">
                    <x-heroicon-o-printer class="w-4 h-4" />
                    Cetak PDF
                </button>
            </div>
        </div>

        <!-- Printable Invoice Area -->
        <div class="p-10 md:p-14 bg-white" id="printable-area">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-16">
                <div>
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-100">
                            <x-heroicon-s-beaker class="w-5 h-5 text-white" />
                        </div>
                        <span class="text-xl font-black text-gray-900 tracking-tight">InventApp</span>
                    </div>
                    <div class="space-y-1">
                        <h1 class="text-4xl font-black text-gray-900 tracking-tight">Invoice</h1>
                        <p class="text-gray-400 font-bold text-sm tracking-wide">#{{ $borrowing->invoice_code ?? 'INV-' . $borrowing->created_at->format('Ymd') . '-' . sprintf('%04d', $borrowing->id) }}</p>
                    </div>
                </div>
                <div class="text-left md:text-right space-y-4">
                    <div class="inline-flex flex-col">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status Peminjaman</span>
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest inline-block w-fit md:ml-auto bg-emerald-100 text-emerald-700">
                            {{ strtoupper($borrowing->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Selesai</p>
                        <p class="text-sm font-bold text-gray-900">{{ $borrowing->updated_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Client & Subject Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Peminjam</h4>
                    <div class="space-y-1">
                        <p class="text-base font-black text-gray-900">{{ $borrowing->peminjam->username }}</p>
                        <p class="text-xs font-bold text-gray-500">{{ $borrowing->peminjam->no_induk }}</p>
                    </div>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Petugas Verifikasi</h4>
                    <div class="space-y-1">
                        <p class="text-base font-black text-gray-900">{{ $borrowing->petugas ? $borrowing->petugas->username : '-' }}</p>
                        <p class="text-xs font-bold text-gray-500 italic">Official Staff Verified</p>
                    </div>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Timeline Peminjaman</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between md:justify-end gap-4">
                            <span class="text-xs font-bold text-gray-400">Tgl Pinjam</span>
                            <span class="text-xs font-black text-gray-900">{{ $borrowing->tgl_pinjam ? $borrowing->tgl_pinjam->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between md:justify-end gap-4">
                            <span class="text-xs font-bold text-gray-400">Tenggat</span>
                            <span @class(['text-xs font-black', 'text-indigo-600'=> $lateDays <= 0, 'text-rose-600'=> $lateDays > 0])>{{ $borrowing->tgl_pengembalian->format('d/m/Y') }}</span>
                        </div>
                        @if($lateDays > 0)
                        <div class="flex items-center justify-between md:justify-end gap-4">
                            <span class="text-xs font-bold text-rose-400">Keterlambatan</span>
                            <span class="text-xs font-black text-rose-600">{{ $lateDays }} Hari</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items & Fines Table -->
            <div class="mb-16">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-900/5">
                            <th class="py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest w-12">No.</th>
                            <th class="py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Deskripsi Alat</th>
                            <th class="py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-24">Jumlah</th>
                            <th class="py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Denda (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($borrowing->details as $index => $detail)
                        @php
                        $rowTotalFine = $detail->denda_final ?? 0;
                        $baseDendaPerAlat = $detail->alat->denda ?? 0;

                        // Total Keterlambatan = Rate * Qty * Days
                        $rowTotalLateFine = $lateDays * ($baseDendaPerAlat * $detail->jumlah);

                        // Denda Tambahan = Total - Late
                        $rowAdditionalFine = max(0, $rowTotalFine - $rowTotalLateFine);
                        @endphp
                        <tr>
                            <td class="py-6 text-sm font-bold text-gray-400 align-top">{{ $index + 1 }}</td>
                            <td class="py-6">
                                <p class="text-sm font-black text-gray-900">{{ $detail->alat->nama }}</p>
                                <div class="flex flex-col gap-1.5 mt-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-gray-400 underline decoration-indigo-200 underline-offset-4">Denda Per Hari: Rp{{ number_format($baseDendaPerAlat) }}</span>
                                    </div>

                                    @if($lateDays > 0)
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div>
                                        <span class="text-[10px] text-rose-500 font-bold italic">
                                            Keterlambatan: Rp{{ number_format($baseDendaPerAlat) }} x {{ $detail->jumlah }} unit x {{ $lateDays }} hari = Rp{{ number_format($rowTotalLateFine) }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-6 text-sm font-black text-center text-gray-900 align-top">{{ $detail->jumlah }} Units</td>
                            <td class="py-6 text-sm font-black text-right text-gray-900 align-top">
                                <div class="flex flex-col items-end">
                                    <span class="text-base text-gray-900 underline decoration-gray-100 underline-offset-8 decoration-2">{{ number_format($rowTotalFine) }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-900">
                            <td colspan="3" class="py-6 text-right text-sm font-black text-gray-900 uppercase tracking-widest">Total Denda</td>
                            <td class="py-6 text-right text-xl font-black text-indigo-600">
                                Rp {{ number_format($borrowing->denda ?? 0) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Footer Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-end">
                <div class="space-y-6 no-print">
                    <div class="p-6 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Informasi Pembayaran</p>
                        <p class="text-xs text-indigo-900/80 leading-relaxed font-bold">
                            Invoice ini merupakan bukti sah penyelesaian pengembalian alat. Segala bentuk denda yang tertera telah disepakati oleh peminjam dan petugas pada saat pengembalian.
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-center md:items-end gap-6 text-center md:text-right">
                    <div class="p-4 bg-white border border-gray-100 rounded-2xl shadow-sm no-print">
                        <x-heroicon-o-qr-code class="w-20 h-20 text-gray-900" />
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Diverifikasi Oleh</p>
                        <p class="text-[10px] font-black text-indigo-600 uppercase">{{ Auth::user()->username }}</p>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest italic">Official Timestamp: {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Footer Strip (Web Only) -->
        <div class="bg-indigo-600 px-8 py-4 flex justify-between items-center text-white/80 no-print">
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">© 2026 INVENTAPP STAFF SYSTEM</span>
            <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">Return Finalized • ref #{{ $borrowing->id }}</span>
        </div>
    </div>

    <!-- Quick Actions (Web Only) -->
    <div class="mt-8 flex justify-center no-print">
        <a href="{{ route('staff.transactions.index') }}" class="flex items-center gap-2.5 text-xs font-black text-gray-400 hover:text-indigo-600 transition-all uppercase tracking-widest group">
            <x-heroicon-o-arrow-left class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
            Kembali ke Dashboard Transaksi
        </a>
    </div>
</div>

<style>
    /* Print optimizations & OKLCH Fixes */
    #printable-area,
    #printable-area * {
        --tw-bg-opacity: 1 !important;
        --tw-text-opacity: 1 !important;
        --tw-border-opacity: 1 !important;
    }

    .bg-indigo-600 {
        background-color: #4f46e5 !important;
    }

    .text-indigo-600 {
        color: #4f46e5 !important;
    }

    .bg-indigo-50 {
        background-color: #f5f7ff !important;
    }

    .text-indigo-900 {
        color: #312e81 !important;
    }

    .bg-emerald-100 {
        background-color: #d1fae5 !important;
    }

    .text-emerald-700 {
        color: #047857 !important;
    }

    .bg-rose-100 {
        background-color: #ffe4e6 !important;
    }

    .text-rose-700 {
        color: #be123c !important;
    }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-size: 11pt;
        }

        .no-print,
        nav,
        footer {
            display: none !important;
        }

        main {
            padding: 0 !important;
            margin: 0 !important;
        }

        .max-w-4xl {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #invoice-card {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        #printable-area {
            padding: 1cm 1.5cm !important;
        }

        table thead th {
            border-bottom-width: 1px !important;
        }

        h1 {
            font-size: 24pt !important;
        }

        .text-base {
            font-size: 11pt !important;
        }

        .text-sm {
            font-size: 10pt !important;
        }

        .py-6 {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }

        .mb-16 {
            margin-bottom: 1.5rem !important;
        }

        .mb-12 {
            margin-bottom: 1rem !important;
        }
    }
</style>

<script>
    function forceRgbColor(colorStr) {
        if (!colorStr || !colorStr.includes('oklch')) return colorStr;
        try {
            const canvas = document.createElement('canvas');
            canvas.width = 1;
            canvas.height = 1;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = colorStr;
            ctx.fillRect(0, 0, 1, 1);
            const data = ctx.getImageData(0, 0, 1, 1).data;
            return `rgb(${data[0]}, ${data[1]}, ${data[2]})`;
        } catch (e) {
            return colorStr;
        }
    }

    function cleanOklchProperties(container) {
        const walkers = document.createTreeWalker(container, NodeFilter.SHOW_ELEMENT, null, false);
        const props = ['backgroundColor', 'color', 'borderColor', 'outlineColor', 'fill', 'stroke'];
        const clean = (el) => {
            const style = window.getComputedStyle(el);
            props.forEach(prop => {
                const val = style[prop];
                if (val && val.includes('oklch')) el.style[prop] = forceRgbColor(val);
            });
        };
        clean(container);
        let node = container;
        while (node = walkers.nextNode()) clean(node);
    }

    function prepareAndPrint() {
        document.title = '{{ $filename }}';
        cleanOklchProperties(document.getElementById('printable-area'));
        setTimeout(() => {
            window.print();
        }, 500);
    }
</script>
@endsection