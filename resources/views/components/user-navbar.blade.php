<div x-data="{ 
    isMenuOpen: false,
    search: '{{ request('search') }}',
    doSearch() {
        let url = new URL(window.location.protocol + '//' + window.location.host + '/borrow');
        if (this.search) url.searchParams.set('search', this.search);
        let currentCat = '{{ request('category') }}';
        if (currentCat) url.searchParams.set('category', currentCat);
        window.location.href = url.toString();
    }
}">
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50 border-b border-gray-100">
        <!-- Top Header (Optional small text line) -->
        <div class="hidden lg:block bg-gray-50 border-b border-gray-100">
            <div class="max-w-7xl auto px-4 sm:px-6 lg:px-8 py-1.5 flex justify-between items-center text-[11px] text-gray-500 font-medium">
                <div class="flex gap-4">
                    <a href="#" class="hover:text-indigo-600">Tentang InventApp</a>
                    <a href="#" class="hover:text-indigo-600">Mitra InventApp</a>
                    <a href="#" class="hover:text-indigo-600">Mulai Meminjam</a>
                    <a href="#" class="hover:text-indigo-600">Promo</a>
                    <a href="#" class="hover:text-indigo-600">Pusat Edukasi</a>
                </div>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-indigo-600 flex items-center gap-1">
                        <x-heroicon-m-device-phone-mobile class="w-3 h-3" />
                        Download App
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Navbar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 lg:h-20 gap-4 lg:gap-8">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('user.borrow.index') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-100 group-hover:scale-105 transition-transform">
                            <x-heroicon-s-beaker class="w-6 h-6 text-white" />
                        </div>
                        <span class="text-2xl font-black text-indigo-600 tracking-tight hidden md:block">InventApp</span>
                    </a>
                </div>

                <!-- Category (Desktop) -->
                <div class="hidden lg:flex items-center">
                    <button @click="isMenuOpen = !isMenuOpen" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3 py-2 flex items-center gap-1">
                        Kategori
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl relative group">
                    <div class="relative">
                        <input type="text"
                            x-model="search"
                            @keydown.enter="doSearch()"
                            class="w-full bg-white border border-gray-200 rounded-lg pl-4 pr-10 py-2.5 text-sm focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-100 transition-all placeholder:text-gray-400 group-hover:border-gray-300"
                            placeholder="Cari alat favoritmu di sini...">
                        <button @click="doSearch()" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-indigo-600 transition-colors">
                            <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Search Suggestions (Desktop Overlay - can be expanded) -->
                    <div class="hidden group-focus-within:block absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 p-4 z-50">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Pencarian Populer</p>
                        <div class="flex flex-wrap gap-2">
                            <button @click="search = 'Kamera Sony'; doSearch()" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 text-xs text-gray-600 hover:text-indigo-700 rounded-full border border-gray-100 transition-colors">Kamera Sony</button>
                            <button @click="search = 'Lensa Canon'; doSearch()" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 text-xs text-gray-600 hover:text-indigo-700 rounded-full border border-gray-100 transition-colors">Lensa Canon</button>
                            <button @click="search = 'Tripod'; doSearch()" class="px-3 py-1.5 bg-gray-50 hover:bg-indigo-50 text-xs text-gray-600 hover:text-indigo-700 rounded-full border border-gray-100 transition-colors">Tripod</button>
                        </div>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2 lg:gap-5">
                    <!-- Cart/Basket -->
                    <a href="#" @click.prevent="$store.cart.showDrawer = true" class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-all group">
                        <x-heroicon-o-shopping-cart class="w-6 h-6" />
                        <span x-show="$store.cart.totalItems > 0" x-text="$store.cart.totalItems" class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm scale-90 group-hover:scale-110 transition-transform"></span>
                    </a>

                    <div class="hidden lg:block w-[1px] h-6 bg-gray-200"></div>

                    <!-- Auth/Profile -->
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs ring-2 ring-white">
                                {{ substr(Auth::user()->username, 0, 1) }}
                            </div>
                            <span class="text-sm font-semibold text-gray-700 hidden lg:block">{{ Auth::user()->username }}</span>
                            <x-heroicon-m-chevron-down class="w-4 h-4 text-gray-400" />
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                            <a href="{{ route('user.borrow.history') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                <x-heroicon-o-user class="w-5 h-5 text-gray-400" /> Profil Saya
                            </a>
                            <a href="{{ route('user.borrow.history') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                <x-heroicon-o-receipt-percent class="w-5 h-5 text-gray-400" /> History Pinjam
                            </a>
                            <div class="my-1 border-t border-gray-100"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                    <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5" /> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-indigo-600 hover:bg-indigo-50 rounded-lg border border-indigo-600 transition-colors">Masuk</a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Search (Small Bar) -->
        <div class="lg:hidden px-4 pb-3">
            <div class="relative">
                <input type="text" x-model="search" @keydown.enter="doSearch()"
                    class="w-full bg-gray-50 border border-gray-100 rounded-lg pl-4 pr-10 py-2 text-xs focus:outline-none focus:border-indigo-600 focus:bg-white transition-all"
                    placeholder="Cari kamera, tripod, mic...">
                <x-heroicon-o-magnifying-glass class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            </div>
        </div>
    </nav>

    <!-- Category Overlay / Menu (Desktop expanded) -->
    <div x-show="isMenuOpen" x-cloak @click.away="isMenuOpen = false" x-transition class="fixed top-20 left-0 right-0 bg-white shadow-2xl z-40 border-b border-gray-100 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex gap-12">
            <div class="w-64">
                <h4 class="text-sm font-bold text-gray-900 mb-4">Urutkan</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600">Populer</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600">Terbaru</a></li>
                    <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600">Stok Terbanyak</a></li>
                </ul>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-gray-900 mb-4 text-center">Jelajahi Kategori</h4>
                <div class="grid grid-cols-4 gap-6">
                    @php $categories = \App\Models\Kategori::all(); @endphp
                    @foreach($categories as $cat)
                    <a href="{{ route('user.borrow.index', ['category' => $cat->id]) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            <x-heroicon-o-tag class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $cat->nama }}</p>
                            <p class="text-[10px] text-gray-400">{{ $cat->alat()->count() }} Produk</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>