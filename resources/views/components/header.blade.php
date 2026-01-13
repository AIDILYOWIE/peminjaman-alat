<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20" x-data="{ notifOpen: false }">
    <div class="flex items-center gap-4">
        <button class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
            <x-heroicon-o-bars-3 class="w-6 h-6" />
        </button>
        <h1 class="text-xl font-semibold text-gray-800">
            @yield('header', 'Dashboard')
        </h1>
    </div>

    <div class="flex items-center gap-4">
        <!-- Notification Dropdown -->
        <div class="relative">
            <button
                @click="notifOpen = !notifOpen"
                class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 relative transition-colors cursor-pointer">
                <x-heroicon-o-bell class="w-6 h-6" />
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
            </button>

            <!-- Dropdown Panel -->
            <div
                x-show="notifOpen"
                @click.away="notifOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50"
                style="display: none;">

                <!-- Header -->
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-gray-800">Notifikasi</h3>
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">3</span>
                    </div>
                    <button class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">Tandai semua dibaca</button>
                </div>

                <!-- Notification Items -->
                <div class="max-h-96 overflow-y-auto">
                    <!-- Notification Item 1 -->
                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer border-b border-gray-50">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <x-heroicon-o-clock class="w-5 h-5 text-red-600" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 mb-0.5">Batas Waktu Pengembalian</p>
                                <p class="text-xs text-gray-600 leading-relaxed">Lensa Canon 50mm harus dikembalikan hari ini sebelum 16:00.</p>
                                <p class="text-xs text-gray-400 mt-1">2 jam lalu</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Item 2 -->
                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer border-b border-gray-50">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-yellow-600" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 mb-0.5">Stok Menipis</p>
                                <p class="text-xs text-gray-600 leading-relaxed">Kamera Sony Alpha tersisa 1 unit.</p>
                                <p class="text-xs text-gray-400 mt-1">5 jam lalu</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Item 3 -->
                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer border-b border-gray-50">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 mb-0.5">Pengembalian Berhasil</p>
                                <p class="text-xs text-gray-600 leading-relaxed">Tripod Manfrotto telah dikembalikan oleh Sarah Wijaya.</p>
                                <p class="text-xs text-gray-400 mt-1">1 hari lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center justify-center gap-1">
                        Lihat Semua Notifikasi
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile -->
        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
            <div class="text-right hidden md:block">
                <p class="text-sm font-medium text-gray-700">Courtney Henry</p>
                <p class="text-xs text-gray-500">Admin Staff</p>
            </div>
            <img src="https://ui-avatars.com/api/?name=Courtney+Henry&background=random" alt="Avatar" class="w-9 h-9 rounded-full border border-gray-200">
        </div>
    </div>
</header>