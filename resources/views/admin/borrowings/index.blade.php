@extends('layouts.app')

@section('header', 'Data Peminjaman')

@section('content')
<div class="space-y-6" x-data="{ 
    detailOpen: false, 
    isSubmitting: false,
    isLoading: true,
    isEditing: false,
    isRescheduling: false,
    selectedBorrowing: {},
    init() {
        // Simulate loading delay for skeleton
        setTimeout(() => {
            this.isLoading = false;
        }, 1500);

        // Real-time fine calculation
        setInterval(() => {
            if (this.detailOpen && this.form.status === 'dipinjam') {
                this.calculateLiveFine();
            }
        }, 1000); // Update every second for smooth UI
    },
    form: {
        id: null,
        user_id: null,
        name: '',
        no_induk: '',
        role: '',
        tools: '',
        qty: '',
        status: '',
        status_label: '',
        status_color: '',
        borrow_date: '',
        return_date: '',
        return_date_raw: '',
        fine: 0,
        staff_name: '-',
        email: '',
        note: '',
        details: [],
        remaining_duration: 0,
        total_fine_rate: 0,
        return_date_iso: '',
        live_fine: 0
    },
    openDetail(borrowing) {
        this.selectedBorrowing = JSON.parse(JSON.stringify(borrowing));
        this.form = JSON.parse(JSON.stringify(borrowing));
        this.detailOpen = true;
        this.isEditing = false;
        this.isRescheduling = false;
    },
    toggleEdit() {
        this.isEditing = !this.isEditing;
    },
    cancelEdit() {
        this.isEditing = false;
        this.form = { ...this.selectedBorrowing };
    },
    confirmEdit() {
        this.isSubmitting = true;
        this.$refs.editBorrowingForm.submit();
    },
    closeDetail() {
        this.detailOpen = false;
        this.isEditing = false;
        this.isRescheduling = false;
    },
    confirmDelete() {
        this.$dispatch('open-confirm', {
            title: 'Hapus Peminjaman',
            message: 'Apakah Anda yakin ingin menghapus peminjaman ini? Tindakan ini tidak dapat dibatalkan.',
            confirmText: 'Ya, Hapus',
            onConfirm: () => {
                this.isSubmitting = true;
                this.$refs.deleteForm.submit();
            }
        });
    },
    toggleReschedule() {
        this.isRescheduling = !this.isRescheduling;
    },
    cancelReschedule() {
        this.isRescheduling = false;
        this.form.tgl_pengembalian = this.selectedBorrowing.tgl_pengembalian;
        this.form.return_date_raw = this.selectedBorrowing.return_date_raw;
        this.form.note = this.selectedBorrowing.note;
    },
    confirmReschedule() {
        this.isSubmitting = true;
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/borrowings/${this.form.id}/reschedule`;
        
        let csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        let method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PATCH';
        
        let dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = 'tgl_pengembalian';
        dateInput.value = this.form.return_date_raw;

        let noteInput = document.createElement('input');
        noteInput.type = 'hidden';
        noteInput.name = 'keterangan';
        noteInput.value = this.form.note;
        
        form.appendChild(csrf);
        form.appendChild(method);
        form.appendChild(dateInput);
        form.appendChild(noteInput);
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
    },
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
    'class' => 'w-full min-w-[150px] sm:min-w-[500px] '
    ],
    [
    'label' => 'Status',
    'key' => 'status_label',
    'component' => 'badge',
    'map' => ['color' => 'status_color'],
    'class' => 'w-px whitespace-nowrap'
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
        :exportRoute="route('admin.borrowings.export')"
        onRowClick="openDetail($row)"
        addButtonText="Tambah"
        :addButtonRoute="route('admin.borrowings.create')" />

    <x-slide-over
        open="detailOpen"
        title="Peminjaman"
        isEditing="isEditing"
        onClose="closeDetail()"
        onToggleEdit="toggleEdit()"
        onConfirm="confirmEdit()"
        onCancel="cancelEdit()"
        onDelete="confirmDelete()"
        hasActions="form.status === 'pending'">

        <x-slot:headerActions>
            <template x-if="form.status === 'dipinjam' && !isRescheduling">
                <button @click="toggleReschedule()" class="cursor-pointer p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 active:scale-90" title="Sesuaikan">
                    <x-heroicon-o-adjustments-horizontal class="w-5 h-5" />
                </button>
            </template>

            <template x-if="isRescheduling">
                <div class="flex items-center gap-2">
                    <button @click="confirmReschedule()"
                        :disabled="isSubmitting"
                        class="cursor-pointer px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm active:scale-95 disabled:opacity-70">
                        <template x-if="typeof isSubmitting === 'undefined' || !isSubmitting">
                            <span>Simpan</span>
                        </template>
                        <template x-if="typeof isSubmitting !== 'undefined' && isSubmitting">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Memproses...</span>
                            </div>
                        </template>
                    </button>
                    <button @click="cancelReschedule()" class="cursor-pointer px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm active:scale-95">
                        Batal
                    </button>
                </div>
            </template>
        </x-slot:headerActions>

        <!-- Header: Hero Status -->
        <div class="relative bg-gradient-to-br from-indigo-500 to-indigo-600 sm:p-8 p-4 text-white">
            <div class="flex items-start justify-between sm:mb-6 mb-4">
                <div class="flex-1">
                    <div class="inline-flex items-center sm:gap-2 gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium mb-3">
                        <x-heroicon-s-tag class="w-3 h-3" />
                        <span x-text="form.status_label"></span>
                    </div>
                    <h3 class="sm:text-2xl text-xl font-bold truncate max-w-[200px]" x-text="form.name"></h3>
                    <p class="text-indigo-100 sm:text-sm text-xs" x-text="form.role"></p>
                </div>
                <div class="flex-shrink-0 w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <x-heroicon-o-calendar-days class="w-10 h-10 text-white/80" />
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20 text-left">
                    <div class="text-xs text-indigo-100 mb-1">Jumlah Item</div>
                    <div class="sm:text-lg text-base font-bold" x-text="form.qty"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20 text-left">
                    <div class="text-xs text-indigo-100 mb-1">Denda</div>
                    <div class="sm:text-lg text-base font-bold" x-text="'Rp ' + (form.status === 'dipinjam' ? (form.live_fine || 0).toLocaleString('id-ID') : (form.fine || 0).toLocaleString('id-ID'))"></div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20 text-left">
                    <div class="text-xs text-indigo-100 mb-1">Sisa Durasi</div>
                    <div class="sm:text-lg text-base font-bold" x-text="form.remaining_duration"></div>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <form x-ref="editBorrowingForm" :action="'/admin/borrowings/' + form.id" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="user_id" :value="form.user_id">

                <!-- Tools List Card -->
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <template x-if="!isEditing">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                    <x-heroicon-o-cube class="w-5 h-5 text-indigo-600" />
                                </div>
                                <h4 class="text-sm font-bold text-gray-900">Alat yang Dipinjam</h4>
                            </div>
                            <div class="space-y-3">
                                <template x-for="tool in form.details" :key="tool.name">
                                    <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100">
                                        <span class="text-sm font-medium text-gray-700" x-text="tool.name"></span>
                                        <span class="text-xs font-semibold bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100 text-indigo-600" x-text="tool.jumlah + ' Unit'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template x-if="isEditing">
                        <x-input.tool-list
                            label="Edit Daftar Alat"
                            :items="$items"
                            x-init="populate(form.details)"
                            classItem="" />
                    </template>
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
                                    <x-input.date ::disabled="!isEditing && !isRescheduling" name="return_date" label="Batas Kembali" required="true" x-model="form.return_date_raw" />
                                </div>
                                <div>
                                    <x-input.date ::disabled="!isEditing" name="borrow_date" label="Waktu Pinjam" required="true" x-model="form.borrow_date_raw" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Petugas Approval</label>
                                    <p class="text-sm font-medium text-gray-900" x-text="form.staff_name"></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Catatan/Keperluan</label>
                                    <template x-if="!isRescheduling && !isEditing">
                                        <p class="text-sm font-medium text-gray-600 italic" x-text="form.note || '-'"></p>
                                    </template>
                                    <template x-if="isRescheduling || isEditing">
                                        <textarea name="keterangan" x-model="form.note" class="p-2 w-full text-xs border-gray-200 rounded-lg focus:outline-none border border-gray-200" rows="2"></textarea>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form x-ref="deleteForm" :action="'{{ route('admin.borrowings.index') }}/' + form.id" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </x-slide-over>
</div>
@endsection