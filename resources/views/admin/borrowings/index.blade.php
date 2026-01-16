@extends('layouts.app')

@section('header', 'Data Peminjaman')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isEditing: false, 
    selectedBorrowing: {},
    form: {
        name: '',
        departemen: '',
        tools: '',
        qty: '',
        status: '',
        status_label: '',
        status_color: '',
        borrow_date: '',
        return_date: '',
        item_code: '',
        fine: 0,
        staff_name: '-',
        email: '',
        note: ''
    },
    openDetail(borrowing) {
        this.selectedBorrowing = borrowing;
        this.form = { ...borrowing };
        this.detailOpen = true;
        this.isEditing = false;
    },
    closeDetail() {
        this.detailOpen = false;
        this.isEditing = false;
    },
    toggleEdit() {
        this.isEditing = !this.isEditing;
    },
    cancelEdit() {
        this.form = { ...this.selectedItem };
        this.isEditing = false;
    },
    confirmEdit() {
        // Placeholder for update logic
        this.selectedItem = { ...this.form };
        this.isEditing = false;
        // You would typically call an API here
    }
}">
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
    'key' => 'tools',
    'class' => 'w-full min-w-[150px] sm:min-w-[200px]'
    ],
    [
    'label' => 'Status',
    'key' => 'status_label',
    'component' => 'badge',
    'map' => ['color' => 'status_color'],
    'class' => 'w-px whitespace-nowrap'
    ],
    ];

    $borrowings = [
    [
    'id' => 1,
    'name' => 'Arif Satrio',
    'no_induk' => '2023010101',
    'departemen' => 'RPL',
    'tools' => 'Tripod Manfrotto',
    'item_code' => 'ACC-023',
    'qty' => 1,
    'avatar' => 'heroicon-o-user',
    'status' => 'pending',
    'status_label' => 'Menunggu',
    'status_color' => 'yellow',
    'borrow_date' => '-',
    'return_date' => '17 Jan 2026',
    'fine' => 0,
    'staff_name' => '-',
    'email' => 'arif@gmail.com',
    'note' => 'Untuk kebutuhan praktik studio',
    'category' => 'Alat Fotografi'
    ],
    [
    'id' => 2,
    'name' => 'Budi Staff',
    'no_induk' => '2023010101',
    'departemen' => 'TPM',
    'tools' => 'Monitor 24 inci',
    'item_code' => 'MON-001',
    'qty' => 1,
    'avatar' => 'heroicon-o-user',
    'status' => 'dipinjam',
    'status_label' => 'Dipinjam',
    'status_color' => 'indigo',
    'borrow_date' => '14 Jan 2026',
    'return_date' => '21 Jan 2026',
    'fine' => 0,
    'staff_name' => 'Admin Lab',
    'email' => 'budi@gmail.com',
    'note' => 'Keperluan Lab TPM',
    'category' => 'Elektronik'
    ],
    [
    'id' => 3,
    'name' => 'Dewi Putri',
    'no_induk' => '2023010101',
    'departemen' => 'BC',
    'tools' => 'Camera Sony A7III',
    'item_code' => 'CAM-012',
    'qty' => 1,
    'avatar' => 'heroicon-o-user',
    'status' => 'selesai',
    'status_label' => 'Selesai',
    'status_color' => 'green',
    'borrow_date' => '10 Jan 2026',
    'return_date' => '12 Jan 2026',
    'fine' => 5000,
    'staff_name' => 'Admin Lab',
    'email' => 'dewi@gmail.com',
    'note' => 'Terlambat 1 hari',
    'category' => 'Alat Fotografi'
    ],
    ];
    @endphp

    <x-data-table
        :columns="$columns"
        :rows="$borrowings"
        paginated="true"
        searchPlaceholder="Cari peminjaman..."
        hasFilter="true"
        hasExport="true"
        onRowClick="openDetail($row)"
        addButtonText="Tambah"
        :addButtonRoute="route('admin.borrowings.create')" />

    <x-slide-over
        open="detailOpen"
        title="Peminjaman"
        onClose="closeDetail()"
        onToggleEdit="toggleEdit()"
        onConfirm="confirmEdit()"
        onCancel="cancelEdit()">

        <!-- Header: Hero Status -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 sm:p-8 p-4 text-white">
            <div class="flex items-start justify-between sm:mb-6 mb-4">
                <div class="flex-1">
                    <div class="inline-flex items-center sm:gap-2 gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium mb-3">
                        <x-heroicon-s-tag class="w-3 h-3" />
                        <span x-text="form.status"></span>
                    </div>
                    <h3 class="sm:text-2xl text-xl font-bold sm:mb-2" x-text="form.tools || 'Nama Alat'"></h3>
                    <p class="text-indigo-100 sm:text-sm text-xs" x-text="form.category"></p>
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

            <!-- Timeline Section -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-clock class="w-5 h-5 text-amber-600" />
                    </div>
                    <h4 class="text-sm font-bold text-gray-900">Waktu & Transaksi</h4>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Batas Kembali</label>
                            <input type="date" x-model="form.return_date" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>

                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Petugas Approval</label>
                            <input type="text" x-model="form.staff_name" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">Catatan/Keperluan</label>
                            <input type="text" x-model="form.note" :disabled="!isEditing"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm leading-relaxed italic text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all disabled:bg-transparent disabled:border-transparent disabled:px-0">
                        </div>

                    </div>
                </div>
            </div>

            <!-- Dynamic Actions -->
            <div class="pt-2">
                <!-- If Pending -->
                <div x-show="form.status === 'pending'" class="flex gap-3">
                    <button class="flex-1 px-4 py-3.5 bg-red-50 text-red-600 rounded-xl text-xs font-bold hover:bg-red-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <x-heroicon-o-no-symbol class="w-4 h-4" /> Tolak
                    </button>
                    <button class="flex-2 px-4 py-3.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 active:scale-95 flex items-center justify-center gap-2">
                        <x-heroicon-o-check-circle class="w-4 h-4" /> Setujui Permintaan
                    </button>
                </div>

                <!-- If Active / Dipinjam -->
                <div x-show="form.status === 'dipinjam'" class="space-y-3">
                    <button class="w-full px-4 py-3.5 bg-green-600 text-white rounded-xl text-xs font-bold hover:bg-green-700 transition-all shadow-lg shadow-green-100 active:scale-95 flex items-center justify-center gap-2">
                        <x-heroicon-o-archive-box-arrow-down class="w-4 h-4" /> Selesaikan & Kembalikan
                    </button>
                </div>
            </div>
        </div>
    </x-slide-over>
</div>
@endsection