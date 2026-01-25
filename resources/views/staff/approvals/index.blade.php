@extends('layouts.app')

@section('header', 'Persetujuan Peminjaman')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    selectedBorrowing: {},
    form: {
        name: '',
        departemen: '',
        tools: [],
        qty: '',
        status: '',
        status_label: '',
        status_color: '',
        borrow_date: '',
        return_date: '',
        item_code: '',
        email: '',
        note: '',
        category: '',
        no_induk: '',
        duration: ''
    },
    isLoading: true,
    init() {
        setTimeout(() => {
            this.isLoading = false;
        }, 1000);
    },
    openDetail(borrowing) {
        this.selectedBorrowing = borrowing;
        this.form = { ...borrowing };
        this.detailOpen = true;
    },
    closeDetail() {
        this.detailOpen = false;
    },
    updateStatus(status) {
        this.$dispatch('open-confirm', {
            title: status === 'dipinjam' ? 'Setujui Peminjaman' : 'Tolak Peminjaman',
            message: status === 'dipinjam' ? 'Apakah Anda yakin ingin menyetujui permintaan ini?' : 'Apakah Anda yakin ingin menolak permintaan ini?',
            confirmText: status === 'dipinjam' ? 'Ya, Setujui' : 'Ya, Tolak',
            type: status === 'dipinjam' ? 'info' : 'danger',
            icon: status === 'dipinjam' ? 'heroicon-o-check-badge' : 'heroicon-o-x-circle',
            onConfirm: () => {
                this.submitStatus(status);
            }
        });
    },
    submitStatus(status) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = `/staff/approvals/${this.form.id}/status`;
        
        let csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        let method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PATCH';

        let statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        form.appendChild(csrf);
        form.appendChild(method);
        form.appendChild(statusInput);
        document.body.appendChild(form);
        form.submit();
    }
}">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu Persetujuan</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['pending'] }}</h3>
                </div>
                <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                    <x-heroicon-o-clock class="w-6 h-6" />
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Disetujui Hr Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['approved_today'] }}</h3>
                </div>
                <div class="p-3 bg-green-50 rounded-xl text-green-600">
                    <x-heroicon-o-check-circle class="w-6 h-6" />
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Ditolak Hr Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['rejected_today'] }}</h3>
                </div>
                <div class="p-3 bg-red-50 rounded-xl text-red-600">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </div>
            </div>
        </div>
    </div>

    @php
    $columns = [
    [
    'label' => 'Peminjam',
    'key' => 'name',
    'component' => 'info',
    'map' => ['subtitle' => 'departemen', 'icon' => 'avatar'],
    'class' => 'w-full'
    ],
    [
    'label' => 'Alat',
    'key' => 'tools_list',
    'class' => 'w-full sm:min-w-[200px] xl:min-w-[400px]'
    ],
    [
    'label' => 'Tgl Pengembalian',
    'key' => 'return_date',
    'align' => 'text-center',
    'class' => 'whitespace-nowrap w-px'
    ],
    [
    'label' => 'Jumlah',
    'key' => 'qty',
    'params' => [
    'hidden' => 'hidden sm:table-cell',
    ],
    'align' => 'text-center'
    ],
    ];

    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$borrowings"
        paginated="true"
        searchPlaceholder="Cari permintaan..."
        :hasFilter="false"
        :hasExport="false"
        onRowClick="openDetail($row)" />

    <x-slide-over
        open="detailOpen"
        title="Persetujuan"
        onClose="closeDetail()"
        :hasActions="false">

        <!-- Header: Hero Status (Fokus Alat) -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 sm:p-8 p-4 text-white">
            <div class="flex items-start justify-between sm:mb-6 mb-4">
                <div class="flex-1 min-w-0">
                    <!-- Badge Kategori -->
                    <div class="inline-flex items-center sm:gap-2 gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase tracking-wider mb-3 border border-white/10">
                        <x-heroicon-s-cube class="w-3 h-3" />
                        <span x-text="form.category"></span>
                    </div>
                    <!-- Daftar Alat sebagai Judul Utama -->
                    <h3 class="sm:text-2xl text-xl font-bold sm:mb-1 truncate pr-4" x-text="form.tools_list" :title="form.tools_list"></h3>
                    <!-- Subtitle: Peminjam & ID Transaksi -->
                    <p class="text-indigo-100 sm:text-sm text-xs opacity-90" x-text="'Peminjam: ' + form.name"></p>
                </div>
                <!-- Icon Alat -->
                <div class="flex-shrink-0 w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 shadow-inner">
                    <x-heroicon-o-camera class="w-8 h-8 text-white/80" />
                </div>
            </div>

            <!-- Overview Stats: Fokus pada Persiapan Alat -->
            <div class="grid grid-cols-2 gap-3">
                <div class="cols-span-1 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Jumlah</div>
                    <div class="text-base font-bold" x-text="form.qty"></div>
                </div>
                <div class="cols-span-1 bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                    <div class="text-xs text-indigo-100 mb-1">Durasi</div>
                    <div class="text-base font-bold" x-text="form.duration"></div>
                </div>
            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="sm:p-6 p-4 sm:space-y-6 space-y-4">
            <!-- Daftar Item Detail -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                            <x-heroicon-o-cube class="w-5 h-5 text-indigo-600" />
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">Alat yang Dipinjam</h4>
                    </div>
                    <div class="space-y-3">
                        <template x-for="tool in form.tools" :key="tool.name">
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100">
                                <span class="text-sm font-medium text-gray-700" x-text="tool.name"></span>
                                <span class="text-xs font-semibold bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100 text-indigo-600" x-text="tool.qty + ' Unit'"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

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

            <!-- Timeline Section -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <x-heroicon-o-clock class="w-5 h-5 text-amber-600" />
                        </div>
                        <h4 class="text-sm font-bold text-gray-900">Waktu & Transaksi</h4>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input.date ::disabled="true" name="return_date" label="Batas Kembali" ::required="false" x-model="form.return_date" />
                            </div>
                            <div>
                                <x-input.date ::disabled="true" name="borrow_date" label="Waktu Pinjam" ::required="false" x-model="form.borrow_date" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-2">Catatan/Keperluan</label>
                                <p class="text-sm font-medium text-gray-600 italic" x-text="form.note || '-'"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4 pt-2">
                <button @click="updateStatus('ditolak')" class="cursor-pointer px-4 py-3.5 bg-white border border-red-200 text-red-600 rounded-xl text-xs font-bold hover:bg-red-50 transition-all flex items-center justify-center gap-2 active:scale-95">
                    <x-heroicon-o-x-circle class="w-4 h-4" /> Tolak
                </button>
                <button @click="updateStatus('dipinjam')" class="cursor-pointer px-4 py-3.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 active:scale-95">
                    <x-heroicon-o-check-badge class="w-4 h-4" /> Setujui
                </button>
            </div>
        </div>
    </x-slide-over>
</div>
@endsection