<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20">
    <div class="flex items-center gap-4">
        <button class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
            <x-heroicon-o-bars-3 class="w-6 h-6" />
        </button>
        <h1 class="text-xl font-semibold text-gray-800">
            @yield('header', 'Dashboard')
        </h1>
    </div>

    <div class="flex items-center gap-4">
        <button class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 relative">
            <x-heroicon-o-bell class="w-6 h-6" />
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
        </button>
        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
            <div class="text-right hidden md:block">
                <p class="text-sm font-medium text-gray-700">Courtney Henry</p>
                <p class="text-xs text-gray-500">Admin Staff</p>
            </div>
            <img src="https://ui-avatars.com/api/?name=Courtney+Henry&background=random" alt="Avatar" class="w-9 h-9 rounded-full border border-gray-200">
        </div>
    </div>
</header>