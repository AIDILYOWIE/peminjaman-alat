<!-- Mobile Backdrop -->
<div
    x-show="sidebarOpen"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"
    style="display: none;">
</div>

<aside
    class="bg-white border-r border-gray-200 fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out transform flex flex-col h-screen overflow-y-auto overflow-x-hidden lg:translate-x-0 lg:static lg:z-auto"
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen,
        'w-64': !sidebarCollapsed || sidebarOpen,
        'w-20': sidebarCollapsed && !sidebarOpen,
        'lg:flex': true
    }">

    {{-- Header Sidebar / Logo --}}
    <div class="h-16 flex items-center px-6 border-b border-gray-100 justify-between shrink-0">
        <div class="flex items-center gap-2 font-bold text-xl text-indigo-600 overflow-hidden whitespace-nowrap">
            <x-heroicon-o-beaker class="w-8 h-8 flex-shrink-0" />
            <span x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity>InventApp</span>
        </div>

        <!-- Toggle Desktop / Close Mobile -->
        <div class="flex items-center">
            <!-- Desktop Collapse Toggle -->
            <button
                @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden lg:block p-1.5 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-100 transition-colors cursor-pointer">
                <x-heroicon-o-chevron-double-left
                    class="w-5 h-5 transition-transform duration-300"
                    ::class="sidebarCollapsed ? 'rotate-180' : ''" />
            </button>

            <!-- Mobile Close Button -->
            <button
                @click="sidebarOpen = false"
                class="lg:hidden p-1.5 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-100 transition-colors cursor-pointer">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>
        </div>
    </div>

    {{-- Navigasi Items --}}
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto overflow-x-hidden">
        {{-- Group Main --}}
        <div x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'pb-2' : 'pb-6'">
            <p x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Main</p>
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors group relative"
                x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'justify-center {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }} hover:bg-gray-50 hover:text-indigo-600' : 'gap-3 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}'"
                x-bind:title="(sidebarCollapsed && !sidebarOpen) ? 'Dashboard' : ''">
                <x-heroicon-o-home class="w-5 h-5 opacity-75 flex-shrink-0" />
                <span x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity>Dashboard</span>

                {{-- Tooltip for collapsed state --}}
                <template x-if="sidebarCollapsed && !sidebarOpen">
                    <div class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-50">
                        Dashboard
                    </div>
                </template>
            </a>
        </div>

        {{-- Group Master Data --}}
        <div x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'pb-2' : 'pb-6'">
            <p x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Master Data</p>
            <a href="{{ route('admin.items.index') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors group relative"
                x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'justify-center {{ request()->routeIs('admin.items.*') ? 'bg-indigo-50 text-indigo-700' : '' }} hover:bg-gray-50 hover:text-indigo-600' : 'gap-3 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.items.*') ? 'bg-indigo-50 text-indigo-700' : '' }}'"
                x-bind:title="(sidebarCollapsed && !sidebarOpen) ? 'Data Alat' : ''">
                <x-heroicon-o-cube class="w-5 h-5 opacity-75 flex-shrink-0" />
                <span x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity>Data Alat</span>
            </a>

            <a href="#"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors group relative"
                x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'justify-center {{ request()->routeIs('admin.pengguna.*') ? 'bg-indigo-50 text-indigo-700' : '' }} hover:bg-gray-50 hover:text-indigo-600' : 'gap-3 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.pengguna.*') ? 'bg-indigo-50 text-indigo-700' : '' }}'"
                x-bind:title="(sidebarCollapsed && !sidebarOpen) ? 'Data Pengguna (Soon)' : ''">
                <x-heroicon-o-users class="w-5 h-5 opacity-75 flex-shrink-0" />
                <span x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity class="flex-1">Data Pengguna</span>
                <span x-show="!sidebarCollapsed || sidebarOpen" class="bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs font-medium">Soon</span>
            </a>
        </div>

        {{-- Group Transaksi --}}
        <div x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'pb-2' : 'pb-6'">
            <p x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Transaksi</p>

            <a href="{{ route('user.borrow.index') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors group relative"
                x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'justify-center {{ request()->routeIs('user.borrow.index') ? 'bg-indigo-50 text-indigo-700' : '' }} hover:bg-gray-50 hover:text-indigo-600' : 'gap-3 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('user.borrow.index') ? 'bg-indigo-50 text-indigo-700' : '' }}'"
                x-bind:title="(sidebarCollapsed && !sidebarOpen) ? 'Peminjaman' : ''">
                <x-heroicon-o-shopping-cart class="w-5 h-5 opacity-75 flex-shrink-0" />
                <span x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity>Peminjaman</span>
            </a>

            <a href="{{ route('staff.approvals.index') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors group relative"
                x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'justify-center {{ request()->routeIs('staff.approvals.index') ? 'bg-indigo-50 text-indigo-700' : '' }} hover:bg-gray-50 hover:text-indigo-600' : 'gap-3 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('staff.approvals.index') ? 'bg-indigo-50 text-indigo-700' : '' }}'"
                x-bind:title="(sidebarCollapsed && !sidebarOpen) ? 'Persetujuan' : ''">
                <x-heroicon-o-check-circle class="w-5 h-5 opacity-75 flex-shrink-0" />
                <span x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity class="flex-1">Persetujuan</span>
                <span x-show="!sidebarCollapsed || sidebarOpen" class="bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-medium">3</span>
            </a>

            <a href="{{ route('staff.returns.index') }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors group relative"
                x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'justify-center {{ request()->routeIs('staff.returns.index') ? 'bg-indigo-50 text-indigo-700' : '' }} hover:bg-gray-50 hover:text-indigo-600' : 'gap-3 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('staff.returns.index') ? 'bg-indigo-50 text-indigo-700' : '' }}'"
                x-bind:title="(sidebarCollapsed && !sidebarOpen) ? 'Pengembalian' : ''">
                <x-heroicon-o-arrow-path class="w-5 h-5 opacity-75 flex-shrink-0" />
                <span x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity>Pengembalian</span>
            </a>
        </div>
    </nav>

    <div class="p-4 border-t border-gray-100 shrink-0">
        <form method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 transition-colors group relative"
                x-bind:class="(sidebarCollapsed && !sidebarOpen) ? 'justify-center' : 'gap-3'"
                x-bind:title="(sidebarCollapsed && !sidebarOpen) ? 'Logout' : ''">
                <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 opacity-75 flex-shrink-0" />
                <span x-show="!sidebarCollapsed || sidebarOpen" x-transition.opacity>Logout</span>
            </button>
        </form>
    </div>
</aside>