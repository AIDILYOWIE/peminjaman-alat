@extends('layouts.app')

@section('header', 'Data Pengembalian')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isLoading: false,
    selectedBorrowing: null,
    form: {
        id: null,
        name: '',
        no_induk: '',
        tools: [],
        qty: 0,
        status: '',
        borrow_date: '',
        return_date: '',
        fine: 0,
        email: '',
        note: '',
        category: ''
    },
    isSuccess: false,
    openDetail(borrowing) {
        this.selectedBorrowing = borrowing;
        this.form = { 
            ...borrowing,
            // Ensure tools is an array for manipulation
            tools: JSON.parse(JSON.stringify(borrowing.tools))
        };
        this.isSuccess = false;
        this.detailOpen = true;
    },
    closeDetail() {
        this.detailOpen = false;
    },
    async submitReturn() {
        this.$dispatch('open-confirm', {
            title: 'Konfirmasi Pengembalian',
            message: 'Apakah Anda yakin ingin menyelesaikan pengembalian ini?',
            confirmText: 'Konfirmasi',
            type: 'info',
            icon: 'heroicon-m-check-badge',
            onConfirm: () => this.executeReturn()
        });
    },
    async executeReturn() {
        this.isLoading = true;
        try {
            const response = await fetch(`${window.location.origin}/staff/returns/${this.form.id}/approve`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    details: this.form.tools.reduce((acc, tool) => {
                        acc[tool.id] = {
                            denda_final: tool.denda_final || 0,
                            keterangan: tool.keterangan || ''
                        };
                        return acc;
                    }, {})
                })
            });

            if (response.redirected) {
                window.location.href = response.url;
                return;
            }

            const result = await response.json();
            if (response.ok && result.success) {
                this.isSuccess=true;
                this.form.status='selesai' ;
                // No automatic reload to let them click Print PDF
                // window.location.reload();
            } else {
                alert(result.message || 'Gagal memproses pengembalian' );
            }
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan koneksi: ' + error.message);
        } finally {
            this.isLoading = false;
        }
    }
}">
    @php
    $columns = [
    [
    'label' => 'Peminjam',
    'key' => 'name',
    'component' => 'info',
    'map' => ['subtitle' => 'no_induk', 'icon' => 'avatar'],
    'class' => 'w-full'
    ],
    [
    'label' => 'Daftar Alat',
    'key' => 'tools_list',
    'class' => 'w-full min-w-[400px]'
    ],
    [
    'label' => 'Tenggat',
    'key' => 'return_date',
    'hidden' => 'hidden sm:table-cell',
    'class' => 'w-full min-w-[300px]'
    ],
    [
    'label' => 'Status',
    'key' => 'status',
    'component' => 'badge',
    'map' => ['color' => 'status_color', 'label' => 'status_label']
    ],
    ];

    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$borrowings"
        paginated="true"
        searchPlaceholder="Cari peminjaman..."
        :hasFilter="false"
        :hasExport="false"
        onRowClick="openDetail($row)" />

    <x-slide-over
        open="detailOpen"
        title="Pengembalian"
        onClose="closeDetail()"
        :hasActions="false">

        <!-- Header: Hero Status -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 sm:p-8 p-4 text-white">
            <div class="flex items-start justify-between sm:mb-6 mb-4">
                <div class="flex-1">
                    <div class="inline-flex items-center sm:gap-2 gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium mb-3">
                        <x-heroicon-s-tag class="w-3 h-3" />
                        <span x-text="form.status"></span>
                    </div>
                    <h3 class="sm:text-2xl text-xl font-bold sm:mb-2 text-white" x-text="form.name || 'Nama Peminjam'"></h3>
                    <p class="text-indigo-100 sm:text-sm text-xs" x-text="form.no_induk"></p>
                </div>
                <div class="flex-shrink-0 w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <x-heroicon-o-calendar-days class="w-10 h-10 text-white/80" />
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Jumlah</div>
                    <div class="sm:text-xl text-base font-bold" x-text="form.qty"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Denda</div>
                    <div class="sm:text-xl text-base font-bold" x-text="form.fine"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Tgl Pinjam</div>
                    <div class="sm:text-xs text-base font-bold" x-text="form.borrow_date"></div>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Borrower Info Section -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-identification class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Informasi Peminjam</h4>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">No Induk</label>
                            <input type="text" x-model="form.no_induk" :disabled="true"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Nama Lengkap</label>
                            <input type="text" x-model="form.name" :disabled="true"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Alamat Email</label>
                            <input type="email" x-model="form.email" :disabled="true"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>

                    </div>
                </div>
            </div>

            <!-- Timeline & Status Section -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-clock class="w-5 h-5 text-amber-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Waktu & Tenggat</h4>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tgl Pinjam</p>
                        <p class="text-sm font-medium text-gray-900" x-text="form.borrow_date"></p>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tenggat Kembali</p>
                        <p class="text-sm font-medium text-red-600" x-text="form.return_date"></p>
                    </div>
                </div>
            </div>

            <!-- Item Verification Section -->
            <div class="bg-indigo-50/50 rounded-2xl p-5 border border-indigo-100/50">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-clipboard-document-check class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Verifikasi Alat & Denda Tambahan</h4>
                </div>

                <div class="space-y-6">
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
                </div>
            </div>



            <!-- Dynamic Actions -->
            <div class="pt-2">
                <!-- If Active / Dipinjam -->
                <div x-show="form.status === 'dipinjam' && !isSuccess" class="space-y-3">
                    <button
                        @click="submitReturn()"
                        :disabled="isLoading"
                        class="w-full px-4 py-3.5 bg-green-600 text-white rounded-xl text-xs font-bold hover:bg-green-700 transition-all shadow-lg shadow-green-100 active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!isLoading">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-check-badge class="w-4 h-4" />
                                <span>Konfirmasi</span>
                            </div>
                        </template>
                        <template x-if="isLoading">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses...</span>
                            </div>
                        </template>
                    </button>
                </div>

                <!-- If Success / Selesai (Show Print Button) -->
                <div x-show="isSuccess" class="space-y-4 animate-in fade-in zoom-in duration-300">
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0 animate-bounce">
                            <x-heroicon-s-check class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <p class="text-sm font-bold text-emerald-900">Pengembalian Berhasil!</p>
                            <p class="text-xs text-emerald-700">Silakan cetak invoice untuk peminjam.</p>
                        </div>
                    </div>

                    <a :href="'{{ url('/staff/returns') }}/' + form.id + '/invoice'" target="_blank"
                        class="w-full px-4 py-3.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95 flex items-center justify-center gap-2">
                        <x-heroicon-o-printer class="w-4 h-4" />
                        <span>Cetak PDF Invoice</span>
                    </a>

                    <button @click="window.location.reload()"
                        class="w-full px-4 py-3.5 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                        <span>Selesaikan & Refresh Halaman</span>
                    </button>
                </div>
            </div>
        </div>
    </x-slide-over>
</div>
@endsection