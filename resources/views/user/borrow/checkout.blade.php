@extends('layouts.user')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ 
    basket: [],
    borrowDate: '',
    returnDate: '',
    keterangan: '',
    selectedItem: null,
    isSubmitting: false,
    isDirect: false,
    init() {
        // 1. Check for Direct Borrow first (Exclusive)
        const direct = localStorage.getItem('direct_borrow');
        if (direct) {
            this.basket = [JSON.parse(direct)];
            this.isDirect = true;
            return;
        }

        // 2. Otherwise use standard Basket
        const stored = localStorage.getItem('borrow_basket');
        if (stored) {
            this.basket = JSON.parse(stored);
            this.isDirect = false;
        } else {
            window.location.href = '{{ route('user.borrow.index') }}';
        }
    },
    get totalUnit() {
        return this.basket.reduce((sum, item) => sum + item.qty, 0);
    },
    async submitBorrowing() {
        if (!this.borrowDate) {
            return this.showToast('Harap pilih tanggal peminjaman.', 'error');
        }
        if (!this.returnDate) {
            return this.showToast('Harap pilih tanggal pengembalian.', 'error');
        }

        this.isSubmitting = true;
        try {
            const response = await fetch('{{ route('user.borrow.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    borrow_date: this.borrowDate,
                    return_date: this.returnDate,
                    keterangan: this.keterangan,
                    items: this.basket.map(item => ({
                        alat_id: item.id,
                        jumlah: item.qty
                    }))
                })
            });

            const result = await response.json();
            if (result.success) {
                if (this.isDirect) {
                    localStorage.removeItem('direct_borrow');
                } else {
                    localStorage.removeItem('borrow_basket');
                    if (Alpine.store('cart')) {
                        Alpine.store('cart').items = [];
                    }
                }
                this.showToast(result.message, 'success');
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } else {
                this.showToast(result.message, 'error');
            }
        } catch (error) {
            this.showToast('Terjadi kesalahan saat mengirim permintaan.', 'error');
        } finally {
            this.isSubmitting = false;
        }
    },
    validateQty(item) {
        if (item.qty > item.stock) item.qty = item.stock;
        if (item.qty < 1) item.qty = 1;
        this.save();
    },
    save() {
        if (this.isDirect) {
            localStorage.setItem('direct_borrow', JSON.stringify(this.basket[0]));
        } else {
            localStorage.setItem('borrow_basket', JSON.stringify(this.basket));
            if (Alpine.store('cart')) {
                Alpine.store('cart').items = JSON.parse(JSON.stringify(this.basket));
            }
        }
    },
    showToast(msg, type) {
        window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type: type } }));
    }
}">
    <h1 class="text-2xl font-black text-gray-900 mb-8">Checkout Peminjaman</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Peminjam Info -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <x-heroicon-o-user-circle class="w-5 h-5 text-indigo-500" />
                    Informasi Peminjam
                </h2>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-black text-lg">
                        {{ substr(auth()->user()->username, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ auth()->user()->username }}</p>
                        <p class="text-xs text-gray-500">Peminjam Aktif • ID #{{ auth()->id() }}</p>
                    </div>
                </div>
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Daftar Alat</h2>
                <div class="divide-y divide-gray-100">
                    <template x-for="item in basket" :key="item.id">
                        <div class="py-6 flex justify-between items-start animate-fadeIn">
                            <div class="flex gap-4">
                                <div class="w-16 h-16 bg-gray-50 rounded-xl border border-gray-100 flex-shrink-0 overflow-hidden">
                                    <img :src="'/storage/' + item.gambar" :alt="item.nama" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900" x-text="item.nama"></h3>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1" x-text="item.code"></p>
                                    <div class="mt-2 text-sm font-medium text-gray-600">
                                        <span x-text="item.qty"></span> Unit
                                    </div>
                                </div>
                            </div>

                            <!-- Compact Quantity Controls -->
                            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full p-1 shadow-sm shrink-0">
                                <button
                                    type="button"
                                    @click="if(item.qty > 1) item.qty--; save()"
                                    class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-all active:scale-90">
                                    <x-heroicon-o-minus class="w-3.5 h-3.5" />
                                </button>
                                <input
                                    type="number"
                                    x-model.number="item.qty"
                                    @input="validateQty(item)"
                                    class="w-6 text-center bg-transparent border-none text-xs font-black text-gray-900 focus:ring-0 p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    readonly>
                                <button
                                    type="button"
                                    @click="if(item.qty < item.stock) item.qty++; save()"
                                    :disabled="item.qty >= item.stock"
                                    class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-all active:scale-90 disabled:opacity-20 disabled:cursor-not-allowed">
                                    <x-heroicon-s-plus class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Date & Note -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Detail Peminjaman</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Peminjaman</label>
                        <input type="date" x-model="borrowDate" min="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pengembalian</label>
                        <input type="date" x-model="returnDate" min="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        <p class="mt-2 text-[10px] text-gray-500 italic">* Harap kembalikan alat sebelum jam operasional berakhir pada tanggal tersebut.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Keperluan</label>
                        <textarea x-model="keterangan" rows="3"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-500 focus:bg-white transition-all"
                            placeholder="Contoh: Untuk keperluan tugas mata kuliah Multimedia..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xl sticky top-24">
                <h2 class="text-lg font-black text-gray-900 mb-6 uppercase tracking-wider">Ringkasan Pinjam</h2>

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Unit</span>
                        <span class="font-bold text-gray-900" x-text="totalUnit + ' Alat'"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Biaya Admin</span>
                        <span class="font-bold text-indigo-600">Gratis</span>
                    </div>
                </div>

                <div class="p-4 bg-yellow-50 rounded-2xl border border-yellow-100 mb-8">
                    <div class="flex gap-3">
                        <x-heroicon-s-information-circle class="w-5 h-5 text-yellow-600 flex-shrink-0" />
                        <p class="text-[11px] text-yellow-700 leading-relaxed font-medium">
                            Peminjaman memerlukan persetujuan admin. Pastikan data yang Anda isi sudah benar.
                        </p>
                    </div>
                </div>

                <button @click="submitBorrowing()"
                    :disabled="isSubmitting"
                    class="w-full cursor-pointer     bg-indigo-600 text-white font-semibold py-2 rounded-lg shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3">
                    <template x-if="isSubmitting">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <span x-text="isSubmitting ? 'Memproses...' : 'Ajukan'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>
@endpush