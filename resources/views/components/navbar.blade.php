<aside class="w-64 bg-white border-r border-gray-200 fixed inset-y-0 left-0 z-30 hidden lg:flex flex-col">
    <div class="h-16 flex items-center px-6 border-b border-gray-100">
        <div class="flex items-center gap-2 font-bold text-xl text-indigo-600">
            <x-heroicon-o-beaker class="w-8 h-8" />
            <span>InventApp</span>
        </div>
    </div>

    {{-- Item --}}
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        {{-- Dashboard --}}
        <div class="pb-4">
            <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Main</p>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <x-heroicon-o-home class="w-5 h-5 opacity-75" />
                Dashboard
            </a>
        </div>

        {{-- Master Data (Admin) --}}
        <div class="pb-4">
            <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Master Data</p>
            <a href="{{ route('admin.items.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors {{ request()->routeIs('admin.items.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <x-heroicon-o-cube class="w-5 h-5 opacity-75" />
                Data Alat
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                <x-heroicon-o-users class="w-5 h-5 opacity-75" />
                Data Pengguna
                <span class="ml-auto bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs font-medium">Soon</span>
            </a>
            <a href="{{ route('admin.items.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors {{ request()->routeIs('admin.items.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <x-heroicon-o-cube class="w-5 h-5 opacity-75" />
                Data Kategori
            </a>
        </div>

        {{-- Transactions --}}
        <div class="pb-4">
            <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Transaksi</p>
            <a href="{{ route('user.borrow.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors {{ request()->routeIs('user.borrow.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <x-heroicon-o-shopping-cart class="w-5 h-5 opacity-75" />
                Peminjaman
            </a>
            <a href="{{ route('staff.approvals.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors {{ request()->routeIs('staff.approvals.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <x-heroicon-o-check-circle class="w-5 h-5 opacity-75" />
                Persetujuan
                <span class="ml-auto bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-medium">3</span>
            </a>
            <a href="{{ route('staff.returns.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors {{ request()->routeIs('staff.returns.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <x-heroicon-o-arrow-path class="w-5 h-5 opacity-75" />
                Pengembalian
            </a>
            <a href="{{ route('staff.returns.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors {{ request()->routeIs('staff.returns.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <x-heroicon-o-arrow-path class="w-5 h-5 opacity-75" />
                Pengambilan
            </a>
        </div>
    </nav>

    <div class="p-4 border-t border-gray-100">
        <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 transition-colors">
            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 opacity-75" />
            Logout
        </a>
    </div>
</aside>