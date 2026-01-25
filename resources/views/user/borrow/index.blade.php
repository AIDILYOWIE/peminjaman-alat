@extends('layouts.user')

@section('content')
<div x-data="{
    showDirectModal: false,
    selectedItem: null,
    borrowDate: '',
    returnDate: '',
    keterangan: '',
    qty: 1,
    isSubmitting: false,

    openDirectModal(item) {
        if (item.stock <= 0) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Stok alat sedang habis.', type: 'error' } }));
            return;
        }
        this.selectedItem = item;
        this.qty = 1;
        this.showDirectModal = true;
    },

    validateQty() {
        if (!this.selectedItem) return;
        if (this.qty > this.selectedItem.stock) {
            this.qty = this.selectedItem.stock;
        }
        if (this.qty < 1) {
            this.qty = 1;
        }
    },

    async confirmDirectBorrow() {
        if (!this.borrowDate) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Harap pilih tanggal peminjaman.', type: 'error' } }));
            return;
        }
        if (!this.returnDate) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Harap pilih tanggal pengembalian.', type: 'error' } }));
            return;
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
                    keterangan: this.keterangan || 'Direct Borrow dari Katalog',
                    items: [{
                        alat_id: this.selectedItem.id,
                        jumlah: this.qty
                    }]
                })
            });

            const result = await response.json();
            if (result.success) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: result.message, type: 'success' } }));
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } else {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: result.message, type: 'error' } }));
            }
        } catch (error) {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Terjadi kesalahan sistem.', type: 'error' } }));
        } finally {
            this.isSubmitting = false;
        }
    }
}" class="flex flex-col gap-4">

    <!-- Category Quick Chips -->
    <div class="flex items-center gap-3 overflow-x-auto mt-4 pb-2 no-scrollbar scroll-smooth">
        <a href="{{ route('user.borrow.index') }}"
            class="px-6 py-2.5 {{ !request('category') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-gray-700 hover:bg-gray-50' }} text-sm font-bold rounded-full whitespace-nowrap transition-all border border-transparent shadow-sm">
            Semua
        </a>
        @foreach($categories as $category)
        <a href="{{ route('user.borrow.index', ['category' => $category->id]) }}"
            class="px-6 py-2.5 {{ request('category') == $category->id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-gray-700 hover:bg-gray-50' }} text-sm font-bold rounded-full whitespace-nowrap transition-all border border-gray-100 shadow-sm flex items-center gap-2">
            {{ $category->nama }}
            <span class="text-[10px] {{ request('category') == $category->id ? 'text-indigo-200' : 'text-gray-400' }}">({{ $category->alat_count }})</span>
        </a>
        @endforeach
    </div>

    <!-- Main Grid -->
    @if($items->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
        @foreach($items as $item)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:translate-y-[-4px] transition-all group">
            <!-- Image Area -->
            <div class="aspect-square bg-gray-50 relative overflow-hidden">
                @if($item->gambar && $item->gambar !== 'pending')
                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                <div class="absolute inset-0 flex items-center justify-center text-gray-300">
                    <x-heroicon-o-photo class="w-16 h-16" />
                </div>
                @endif

                @if($item->stock <= 0)
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center p-4">
                    <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-black rounded-lg shadow-lg rotate-[-12deg]">STOK HABIS</span>
            </div>
            @endif
        </div>

        <!-- Info Area -->
        <div class="p-4 flex flex-col h-[180px]">
            <div class="flex-1">
                <span class="inline-block px-2 py-0.5 bg-gray-50 text-gray-500 text-[10px] font-bold rounded uppercase tracking-wider mb-2">
                    {{ $item->kategori->nama ?? 'Unit' }}
                </span>
                <h3 class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors mb-1">
                    {{ $item->nama }}
                </h3>
                <div class="flex items-center gap-1.5 mb-2">
                    <x-heroicon-s-star class="w-3 h-3 text-yellow-400" />
                    <span class="text-[10px] font-bold text-gray-500">4.9 | Terpinjam 100+</span>
                </div>
                <div class="flex items-center justify-end">
                    <div class="flex items-center gap-1">
                        <div class="w-1.5 h-1.5 rounded-full {{ $item->stock > 0 ? 'bg-indigo-600' : 'bg-red-500' }}"></div>
                        <span class="text-[10px] font-bold {{ $item->stock > 0 ? 'text-gray-500' : 'text-red-500' }}">
                            {{ $item->stock > 0 ? "Stok $item->stock" : 'Habis' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-4 flex items-center gap-2">
                @if($item->stock > 0)
                <button @click="openDirectModal(@js($item))" class="w-full flex items-center justify-center gap-1 py-3 bg-indigo-600 text-white text-[11px] font-black tracking-wider rounded-xl transition-all active:scale-95 shadow-lg shadow-indigo-100 cursor-pointer">
                    Pinjam Sekarang
                </button>
                <button @click="$store.cart.add(@js($item))" class="w-max flex items-center justify-center gap-1 p-2 bg-white border-1 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white text-xs font-bold rounded-xl transition-all active:scale-95 shadow-sm cursor-pointer group/cart">
                    <x-heroicon-o-shopping-cart class="w-5 h-5 group-hover/cart:scale-110 transition-transform" />
                </button>
                @else
                <div class="w-full py-3 bg-gray-50 border border-gray-100 text-gray-400 text-[11px] font-black uppercase tracking-wider rounded-xl text-center">
                    Habis
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
<div class="mt-12 flex justify-center">
    {{ $items->links() }}
</div>
@else
<div class="bg-white rounded-3xl border border-gray-100 py-32 flex flex-col items-center justify-center text-center px-6">
    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
        <x-heroicon-o-archive-box-x-mark class="w-12 h-12 text-gray-300" />
    </div>
    <h3 class="text-xl font-black text-gray-900 mb-2">Ups! Alat Tidak Ditemukan</h3>
    <p class="text-gray-500 text-sm max-w-xs mx-auto mb-8">Coba kata kunci lain atau pilih kategori yang berbeda untuk menemukan alat yang Anda cari.</p>
    <a href="{{ route('user.borrow.index') }}" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-100">Reset Pencarian</a>
</div>
@endif

<!-- Direct Borrow Modal -->
<div x-show="showDirectModal"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
    style="display: none;"
    x-cloak>

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
        @click="showDirectModal = false"
        x-show="showDirectModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transition-all transform"
        x-show="showDirectModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight">Konfirmasi Pinjam</h3>
            <button @click="showDirectModal = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
            <!-- Header with Item Summary & Quantity Selector -->
            <template x-if="selectedItem">
                <div class="flex items-start justify-between gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="flex gap-4">
                        <div class="w-14 h-14 rounded-xl bg-white border border-gray-100 overflow-hidden flex-shrink-0">
                            <img :src="'/storage/' + selectedItem.gambar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-indigo-600 uppercase tracking-widest" x-text="selectedItem.kategori?.nama || 'TOOL'"></p>
                            <h4 class="font-bold text-gray-900 leading-tight text-sm" x-text="selectedItem.nama"></h4>
                            <p class="text-[10px] text-gray-400 font-bold mt-1" x-text="'Tersedia: ' + selectedItem.stock + ' Unit'"></p>
                        </div>
                    </div>

                    <!-- Compact Quantity Controls -->
                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full p-1 shadow-xs shrink-0">
                        <button
                            type="button"
                            @click="if(qty > 1) qty--"
                            class="w-6 h-6 flex items-center justify-center text-gray-500 rounded-md transition-all active:scale-90">
                            <x-heroicon-o-minus class="w-3.5 h-3.5" />
                        </button>
                        <input
                            type="number"
                            x-model.number="qty"
                            @input="validateQty"
                            class="w-6 text-center bg-transparent border-none text-xs font-black text-gray-900 focus:ring-0 p-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            readonly>
                        <button
                            type="button"
                            @click="qty++"
                            :disabled="qty >= selectedItem.stock"
                            class="w-6 h-6 flex items-center justify-center text-gray-500 rounded-md transition-all active:scale-90 disabled:opacity-20 disabled:cursor-not-allowed">
                            <x-heroicon-s-plus class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </div>
            </template>

            <!-- Inputs -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-black text-gray-400 mb-2">Tanggal Peminjaman</label>
                    <input type="date" x-model="borrowDate" min="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 mb-2">Tanggal Pengembalian</label>
                    <input type="date" x-model="returnDate" min="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 mb-2">Keterangan (Opsional)</label>
                    <textarea x-model="keterangan" rows="2"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition-all"
                        placeholder="Contoh: Untuk kegiatan praktikum..."></textarea>
                </div>
            </div>

            <!-- Info Note -->
            <div class="flex gap-3 p-4 bg-yellow-50 rounded-2xl border border-yellow-100">
                <x-heroicon-s-information-circle class="w-5 h-5 text-yellow-600 flex-shrink-0" />
                <p class="text-[10px] text-yellow-700 leading-relaxed font-medium">
                    Peminjaman Anda akan berstatus <b>PENDING</b> dan memerlukan persetujuan dari Staff Penjaga sebelum alat dapat diambil.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-6 bg-gray-50 border-t border-gray-100">
            <button @click="confirmDirectBorrow"
                :disabled="isSubmitting"
                class="w-full py-3 bg-indigo-600 text-white font-black rounded-lg shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-3">
                <template x-if="isSubmitting">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="isSubmitting ? 'Memproses...' : 'Ajukan Peminjaman'"></span>
            </button>
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<style>
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .animate-bounce-subtle {
        animation: bounce-subtle 2s infinite;
    }

    @keyframes bounce-subtle {

        0%,
        100% {
            transform: translateX(-50%) translateY(0);
        }

        50% {
            transform: translateX(-50%) translateY(-5px);
        }
    }

    .text-shadow-sm {
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }
</style>
@endpush