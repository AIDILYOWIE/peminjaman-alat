<footer class="bg-white border-t border-gray-200 mt-auto sticky bottom-0 z-20">
    <div class="px-4 lg:px-8 py-2.5 sm:py-4">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-2 sm:gap-4">
            <!-- Logo & Brand - Smaller on mobile -->
            <div class="flex items-center gap-1.5 sm:gap-2 font-bold text-sm sm:text-lg text-indigo-600">
                <x-heroicon-o-beaker class="w-4 h-4 sm:w-6 sm:h-6" />
                <span class="hidden sm:inline">InventApp</span>
            </div>

            <!-- Copyright - Compact on mobile -->
            <p class="text-[10px] sm:text-sm text-gray-500 text-center order-last sm:order-none">
                &copy; {{ date('Y') }} Porto Alat. All rights reserved.
            </p>

            <!-- Links - Compact layout -->
            <div class="flex items-center gap-3 sm:gap-6">
                <a href="#" class="text-[11px] sm:text-sm text-gray-500 hover:text-indigo-600 transition-colors">Bantuan</a>
                <a href="#" class="text-[11px] sm:text-sm text-gray-500 hover:text-indigo-600 transition-colors">Privasi</a>
                <a href="#" class="text-[11px] sm:text-sm text-gray-500 hover:text-indigo-600 transition-colors">Ketentuan</a>
            </div>
        </div>
    </div>
</footer>