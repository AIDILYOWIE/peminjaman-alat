<footer class="bg-white border-t border-gray-200 mt-auto sticky bottom-0 z-20">
    <div class="px-4 lg:px-8 py-4">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-2 font-bold text-lg text-indigo-600">
                <x-heroicon-o-beaker class="w-6 h-6" />
                <span>InventApp</span>
            </div>

            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} Porto Alat. All rights reserved.
            </p>

            <div class="flex items-center gap-6">
                <a href="#" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Bantuan</a>
                <a href="#" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Privasi</a>
                <a href="#" class="text-sm text-gray-500 hover:text-indigo-600 transition-colors">Ketentuan</a>
            </div>
        </div>
    </div>
</footer>