@extends('layouts.user')

@section('content')
<div x-data="{
    isSubmitting: false,

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
                <button @click="$store.cart.buyNow(@js($item))" class="w-full flex items-center justify-center gap-1 py-3 bg-indigo-600 text-white text-[11px] font-black tracking-wider rounded-xl transition-all active:scale-95 shadow-lg shadow-indigo-100 cursor-pointer">
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