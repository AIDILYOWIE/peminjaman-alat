@extends('layouts.app')

@section('header', 'Data Pengembalian')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    processOpen: false,
    isSubmitting: false,
    isLoading: true,
    form: {
        id: null,
        name: '',
        no_induk: '',
        tools: '',
        qty: '',
        borrow_date: '',
        return_date: '',
        fine: 0,
        staff_name: '-',
        note: '',
        status_label: 'Dipinjam',
        total_fine_rate: 0,
        return_date_iso: '',
        live_fine: 0
    },
    activeBorrowings: [],
    searchActive: '',
    isLoadingActive: false,
    init() {
        setTimeout(() => {
            this.isLoading = false;
        }, 1000);

        setInterval(() => {
            if (this.detailOpen) {
                this.calculateLiveFine();
            }
        }, 1000);
    },
    openDetail(item) {
        this.form = { ...item };
        this.detailOpen = true;
    },
    closeDetail() {
        this.detailOpen = false;
    },
    async openProcess() {
        this.processOpen = true;
        this.fetchActiveBorrowings();
    },
    async fetchActiveBorrowings() {
        this.isLoadingActive = true;
        try {
            const response = await fetch(`/admin/returns/create?search=${this.searchActive}`);
            const data = await response.json();
            this.activeBorrowings = data.data;
        } catch (error) {
            console.error('Error fetching active borrowings:', error);
        } finally {
            this.isLoadingActive = false;
        }
    },
    confirmReturn(borrowingId) {
        this.$dispatch('open-confirm', {
            title: 'Proses Pengembalian',
            message: 'Alat sudah dicek dan dalam keadaan baik? Tindakan ini akan menyelesaikan transaksi peminjaman.',
            confirmText: 'Ya, Selesai',
            onConfirm: () => {
                this.isSubmitting = true;
                this.submitReturn(borrowingId);
            }
        });
    },
    submitReturn(borrowingId) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.returns.store') }}';
        
        let csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        let idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'borrowing_id';
        idInput.value = borrowingId;
        
        form.appendChild(csrf);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    },
    calculateLiveFine() {
        if (!this.form.return_date_iso) return;
        
        const deadline = new Date(this.form.return_date_iso);
        deadline.setHours(0, 0, 0, 0);
        
        const now = new Date();
        now.setHours(0, 0, 0, 0);
        
        const diffTime = now - deadline;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays > 0) {
            this.form.live_fine = diffDays * (this.form.total_fine_rate || 0);
        } else {
            this.form.live_fine = 0;
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
    'label' => 'Alat',
    'key' => 'tools',
    'class' => 'w-full min-w-[300px]'
    ],
    [
    'label' => 'Denda',
    'key' => 'fine',
    'class' => 'w-px whitespace-nowrap',
    'format' => 'idr'
    ],
    [
    'label' => 'Batas Kembali',
    'key' => 'return_date',
    'class' => 'w-px whitespace-nowrap text-gray-600 font-medium'
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$returns"
        paginated="true"
        searchPlaceholder="Cari peminjaman aktif..."
        hasFilter="false"
        hasExport="false"
        onRowClick="openDetail($row)"
        :addButtonRoute="null" />

    <!-- Detail History Slide-over -->
    <x-slide-over
        open="detailOpen"
        title="Pengembalian"
        onClose="closeDetail()"
        :hasActions="false">

        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 sm:p-8 p-4 text-white">
            <div class="flex items-start justify-between sm:mb-6 mb-4">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium mb-3">
                        <x-heroicon-s-tag class="w-3 h-3" />
                        <span>Dipinjam</span>
                    </div>
                    <h3 class="sm:text-2xl text-xl font-bold truncate" x-text="form.name"></h3>
                    <p class="text-indigo-100 sm:text-sm text-xs" x-text="form.no_induk"></p>
                </div>
                <div class="flex-shrink-0 w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <x-heroicon-o-arrow-path class="w-10 h-10 text-white/80" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Denda Saat Ini</div>
                    <div class="sm:text-lg text-base font-bold" x-text="'Rp ' + (this.form.live_fine || form.fine || 0).toLocaleString('id-ID')"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-emerald-100 mb-1">Total Qty</div>
                    <div class="sm:text-lg text-base font-bold" x-text="form.qty"></div>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-cube class="w-5 h-5 text-emerald-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Alat yang Dikembalikan</h4>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed font-medium" x-text="form.tools"></p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-clock class="w-5 h-5 text-amber-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Riwayat Waktu</h4>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 mb-1">Pinjam</span>
                            <p class="text-sm font-medium text-gray-900" x-text="form.borrow_date"></p>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 mb-1">Kembali</span>
                            <p class="text-sm font-medium text-gray-900" x-text="form.return_date"></p>
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 mb-1">Petugas Penerima</span>
                        <p class="text-sm font-medium text-gray-900" x-text="form.staff_name"></p>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 mb-1">Catatan</span>
                        <p class="text-sm font-medium text-gray-600 italic" x-text="form.note || '-'"></p>
                    </div>
                </div>
            </div>
        </div>
    </x-slide-over>

    <!-- Process Return Selection Slide-over -->
    <x-slide-over
        open="processOpen"
        title="Proses Pengembalian"
        onClose="processOpen = false"
        :hasActions="false">

        <div class="p-6 space-y-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400" />
                </div>
                <input type="text"
                    x-model="searchActive"
                    @input.debounce.500ms="fetchActiveBorrowings()"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-gray-50/50"
                    placeholder="Cari peminjam atau alat...">
            </div>

            <div class="space-y-3">
                <template x-if="isLoadingActive">
                    <div class="flex flex-col items-center justify-center py-12 space-y-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        <p class="text-sm text-gray-500 font-medium">Memuat data aktif...</p>
                    </div>
                </template>

                <template x-if="!isLoadingActive && activeBorrowings.length === 0">
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                            <x-heroicon-o-magnifying-glass class="w-8 h-8 text-gray-300" />
                        </div>
                        <p class="text-gray-500 font-medium text-sm">Tidak ada peminjaman aktif ditemukan</p>
                    </div>
                </template>

                <template x-for="item in activeBorrowings" :key="item.id">
                    <div class="p-4 bg-white border border-gray-100 rounded-2xl hover:border-indigo-200 transition-all group shadow-sm hover:shadow-md">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 space-y-1">
                                <div class="font-bold text-gray-900" x-text="item.name"></div>
                                <div class="text-xs font-medium text-gray-500" x-text="item.no_induk"></div>
                                <div class="text-sm text-gray-700 font-medium pt-2" x-text="item.tools"></div>
                                <div class="flex items-center gap-3 pt-2">
                                    <div class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Batas: <span x-text="item.return_date"></span></div>
                                </div>
                            </div>
                            <button @click="confirmReturn(item.id)"
                                :disabled="isSubmitting"
                                class="flex-shrink-0 p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all active:scale-95 disabled:opacity-50 cursor-pointer">
                                <x-heroicon-o-check-circle class="w-6 h-6" />
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </x-slide-over>
</div>
@endsection