<!-- Cart Drawer (Slide-over) -->
<div x-data x-show="$store.cart.showDrawer" class="fixed inset-0 z-[100] overflow-hidden" style="display: none;" x-cloak>
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$store.cart.showDrawer = false" x-show="$store.cart.showDrawer" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex">
        <div class="w-screen max-w-md" x-show="$store.cart.showDrawer" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
            <div class="h-full flex flex-col bg-white shadow-2xl">
                <!-- Header -->
                <div class="px-6 py-6 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                            <x-heroicon-s-shopping-cart class="w-6 h-6" />
                        </div>
                        <h2 class="text- font-black text-gray-900 uppercase tracking-tight">
                            Keranjang Anda</h2>
                    </div>
                    <button @click="$store.cart.showDrawer = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>

                <!-- Items List -->
                <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                    <template x-if="$store.cart.items.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <x-heroicon-o-shopping-bag class="w-10 h-10 text-gray-300" />
                            </div>
                            <p class="text-gray-500 font-medium">Keranjang Anda masih kosong</p>
                            <button @click="$store.cart.showDrawer = false" class="mt-4 text-indigo-600 font-bold text-sm hover:underline">Mulai Meminjam</button>
                        </div>
                    </template>

                    <div class="space-y-6">
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="flex gap-4">
                                <div class="w-20 h-20 bg-gray-50 rounded-xl border border-gray-100 overflow-hidden flex-shrink-0">
                                    <img :src="'/storage/' + item.gambar" :alt="item.nama" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-900 leading-tight" x-text="item.nama"></h3>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 block" x-text="item.code"></span>
                                        </div>
                                        <button @click="$store.cart.remove(item.id)" class="text-gray-300 hover:text-red-500 transition-colors">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-lg p-1">
                                            <button @click="$store.cart.updateQty(item.id, -1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-white rounded-md transition-all">-</button>
                                            <span class="text-xs font-bold text-gray-900 min-w-[20px] text-center" x-text="item.qty"></span>
                                            <button @click="$store.cart.updateQty(item.id, 1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-white rounded-md transition-all">+</button>
                                        </div>
                                        <span class="text-[10px] font-bold text-indigo-600">Terpinjam</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer Summary -->
                <div class="p-6 bg-gray-50 border-t border-gray-100 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Total Unit Alat</span>
                        <span class="font-black text-gray-900" x-text="$store.cart.totalItems + ' Item'"></span>
                    </div>
                    <button @click="$store.cart.checkout()" :disabled="$store.cart.items.length === 0" class="w-full py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        Lanjut ke Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Basket Mobile Floating Trigger -->
<button @click="$store.cart.showDrawer = true"
    x-data
    x-show="$store.cart.totalItems > 0"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-20 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    class="fixed bottom-8 left-1/2 -translate-x-1/2 lg:hidden bg-indigo-600 text-white flex items-center gap-3 px-6 py-3 rounded-full shadow-2xl z-50 animate-bounce-subtle">
    <x-heroicon-s-shopping-cart class="w-6 h-6" />
    <span class="text-sm font-black" x-text="$store.cart.totalItems + ' Item Terpilih'"></span>
</button>

<style>
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
</style>