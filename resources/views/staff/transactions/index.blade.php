@extends('layouts.app')

@section('header', 'Manajemen Transaksi')

@section('content')
<div class="space-y-6" x-data="{ 
    tab: '{{ $currentTab }}',
    detailOpen: false, 
    isLoading: false,
    isSuccess: false,
    selectedBorrowing: null,
    form: { id: null, tools: [], status: '' },

    openDetail(borrowing) {
        this.selectedBorrowing = borrowing;
        this.form = { 
            ...borrowing,
            tools: JSON.parse(JSON.stringify(borrowing.tools))
        };
        this.isSuccess = false;
        this.detailOpen = true;
    },

    async updateStatus(status) {
        this.$dispatch('open-confirm', {
            title: status === 'dipinjam' ? 'Setujui Peminjaman' : 'Tolak Peminjaman',
            message: status === 'dipinjam' ? 'Apakah Anda yakin ingin menyetujui permintaan ini?' : 'Apakah Anda yakin ingin menolak permintaan ini?',
            confirmText: status === 'dipinjam' ? 'Setujui' : 'Tolak',
            type: status === 'dipinjam' ? 'info' : 'danger',
            icon: status === 'dipinjam' ? 'heroicon-o-check-badge' : 'heroicon-o-x-circle',
            onConfirm: () => {
                this.submitStatus(status);
            }
        });
    },

    async submitStatus(status) {
        this.isLoading = true;
        try {
            const response = await fetch(`${window.location.origin}/staff/transactions/${this.form.id}/approve`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status })
            });
            const result = await response.json();
            if (response.ok && result.success) {
                window.location.reload();
            } else {
                alert(result.message || 'Gagal memperbarui status');
            }
        } catch (error) {
            alert('Kesalahan koneksi');
        } finally {
            this.isLoading = false;
        }
    },

    async submitReturn() {
        this.$dispatch('open-confirm', {
            title: 'Konfirmasi Pengembalian',
            message: 'Apakah Anda yakin ingin menyelesaikan pengembalian ini?',
            confirmText: 'Selesaikan',
            cancelText: 'Batal',
            onConfirm: () => this.executeReturn()
        });
    },

    async executeReturn() {
        this.isLoading = true;
        try {
            const response = await fetch(`${window.location.origin}/staff/transactions/${this.form.id}/return`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    details: this.form.tools.reduce((acc, tool) => {
                        acc[tool.id] = { denda_final: tool.denda_final || 0, keterangan: tool.keterangan || '' };
                        return acc;
                    }, {})
                })
            });
            const result = await response.json();
            if (result.success) {
                this.isSuccess = true;
                this.form.status = 'selesai';
                this.form.fine = result.fine;
                this.form.tools = result.tools;
            } else {
                alert(result.message || 'Gagal memproses pengembalian');
            }
        } catch (error) {
            alert('Kesalahan koneksi');
        } finally {
            this.isLoading = false;
        }
    }
}">
    <!-- Tab Navigation -->
    <div class="flex flex-wrap items-center justify-between gap-4 bg-white/50 backdrop-blur-md p-2 rounded-2xl border border-white shadow-sm ring-1 ring-gray-100">
        <div class="flex p-1 bg-gray-100/50 rounded-xl">
            <a href="?tab=persetujuan"
                class="px-6 py-2.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2"
                :class="tab === 'persetujuan' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                <x-heroicon-o-check-circle class="w-4 h-4" />
                Persetujuan
                @if($counts['persetujuan'] > 0)
                <span class="bg-red-500 text-white px-1.5 py-0.5 rounded-full text-[10px]">{{ $counts['persetujuan'] }}</span>
                @endif
            </a>
            <a href="?tab=pengembalian"
                class="px-6 py-2.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2"
                :class="tab === 'pengembalian' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                Pengembalian
                <span class="text-gray-400 font-normal">({{ $counts['pengembalian'] }})</span>
            </a>
            <a href="?tab=riwayat"
                class="px-6 py-2.5 rounded-lg text-xs font-bold transition-all flex items-center gap-2"
                :class="tab === 'riwayat' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                <x-heroicon-o-clock class="w-4 h-4" />
                Riwayat
            </a>
        </div>
    </div>

    <!-- Table Section -->
    <div class="mt-6">
        @php
        $columns = [
        ['label' => 'Peminjam', 'key' => 'name', 'component' => 'info', 'map' => ['subtitle' => 'no_induk', 'icon' => 'avatar'], 'class' => 'w-full'],
        ['label' => 'Daftar Alat', 'key' => 'tools_list', 'class' => 'w-full min-w-[300px]'],
        ];

        if($currentTab === 'riwayat') {
        $columns[] = ['label' => 'Total Denda', 'key' => 'fine', 'class' => 'text-right font-bold text-rose-600'];
        } else {
        $columns[] = ['label' => 'Tenggat', 'key' => 'return_date', 'hidden' => 'hidden sm:table-cell'];
        }

        $columns[] = ['label' => 'Status', 'key' => 'status', 'component' => 'badge', 'map' => ['color' => 'status_color', 'label' => 'status_label']];
        @endphp

        <x-data-table
            :columns="$columns"
            :rows="$borrowings"
            paginated="true"
            searchPlaceholder="Cari transaksi..."
            :hasFilter="false"
            canExport="true"
            :exportRoute="route('staff.transactions.export')"
            onRowClick="openDetail($row)">

            @if($currentTab === 'riwayat')
            <x-slot name="headerActions">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Klik baris untuk cetak invoice</span>
            </x-slot>
            @endif
        </x-data-table>
    </div>

    <!-- Detail Slide Over -->
    <x-slide-over open="detailOpen" title="Transaksi" onClose="detailOpen = false" :hasActions="false">
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 p-8 text-white rounded-b-[2rem]">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase mb-3" x-text="form.status"></span>
                    <h3 class="text-2xl font-bold mb-1" x-text="form.name"></h3>
                    <p class="text-indigo-100 text-sm" x-text="form.no_induk"></p>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <x-heroicon-o-document-text class="w-8 h-8 text-white/80" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-6">
                <div class="bg-white/10 p-3 rounded-xl border border-white/20">
                    <p class="text-[10px] text-indigo-100 mb-1">Tgl Pinjam</p>
                    <p class="font-bold text-sm" x-text="form.borrow_date"></p>
                </div>
                <div class="bg-white/10 p-3 rounded-xl border border-white/20">
                    <p class="text-[10px] text-indigo-100 mb-1">Tenggat Kembali</p>
                    <p class="font-bold text-sm" x-text="form.return_date"></p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Tools List -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Daftar Alat</h4>
                <div class="space-y-4">
                    <template x-for="tool in form.tools">
                        <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100">
                            <div>
                                <p class="text-sm font-bold text-gray-900" x-text="tool.name"></p>
                                <p class="text-[10px] text-gray-500" x-text="tool.unit_code || 'Standard'"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-indigo-600" x-text="tool.qty + ' Unit'"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Context Actions -->
            <div class="pt-4">
                <!-- Case: Pending Approval -->
                <div x-show="form.status === 'pending'" class="grid grid-cols-2 gap-3">
                    <button @click="updateStatus('ditolak')" class="cursor-pointer px-4 py-3.5 bg-white border border-red-200 text-red-600 rounded-xl text-xs font-bold hover:bg-red-50 transition-all flex items-center justify-center gap-2 active:scale-95">
                        <x-heroicon-o-x-circle class="w-4 h-4" /> Tolak
                    </button>
                    <button @click="updateStatus('dipinjam')" class="cursor-pointer px-4 py-3.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 active:scale-95">
                        <x-heroicon-o-check-badge class="w-4 h-4" /> Setujui
                    </button>
                </div>

                <!-- Case: Active (Dipinjam) -->
                <div x-show="form.status === 'dipinjam' && !isSuccess" class="space-y-4">
                    <template x-for="(tool, index) in form.tools" :key="index">
                        <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="text-sm font-bold text-gray-900" x-text="tool.name"></h5>
                                    <p class="text-xs text-gray-500" x-text="'Jumlah: ' + tool.qty + ' unit'"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Denda Kerusakan/Kehilangan (Rp)</label>
                                    <input type="number" x-model.number="tool.denda_final"
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Keterangan Kondisi</label>
                                    <textarea x-model="tool.keterangan"
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                        rows="2" placeholder="Contoh: Lensa lecet, baut kendur, dll"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button @click="submitReturn()" :disabled="isLoading" class="w-full px-4 py-4 bg-green-600 text-white rounded-xl text-xs font-bold hover:bg-green-700 transition-all shadow-lg flex items-center justify-center gap-2">
                        <x-heroicon-o-check-badge class="w-4 h-4" />
                        Selesaikan Pengembalian
                    </button>
                </div>

                <!-- Case: Completed / Success Flow -->
                <div x-show="form.status === 'selesai' || isSuccess" class="space-y-4">
                    <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0 animate-bounce">
                            <x-heroicon-s-check class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <p class="text-sm font-bold text-emerald-900">Transaksi Selesai</p>
                            <p class="text-xs text-emerald-700">Invoice siap dicetak untuk peminjam.</p>
                        </div>
                    </div>

                    <div x-show="form.fine > 0" class="p-4 bg-rose-50 rounded-xl border border-rose-100">
                        <p class="text-[10px] text-rose-400 uppercase font-black mb-1">Total Denda Dibayarkan</p>
                        <p class="text-lg font-black text-rose-600" x-text="'Rp ' + Number(form.fine).toLocaleString()"></p>
                    </div>

                    <a :href="'{{ url('/staff/transactions') }}/' + form.id + '/invoice'" target="_blank"
                        class="w-full px-4 py-4 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-xl flex items-center justify-center gap-2">
                        <x-heroicon-o-printer class="w-5 h-5" />
                        Cetak PDF Invoice
                    </a>

                    <button @click="window.location.reload()" class="w-full px-4 py-3 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-200 transition-all mt-4">
                        Tutup & Refresh Halaman
                    </button>
                </div>
            </div>
        </div>
    </x-slide-over>
</div>
@endsection