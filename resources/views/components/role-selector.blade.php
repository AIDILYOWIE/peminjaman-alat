<div class="flex gap-2 p-1.5 bg-gray-100 rounded-xl">
    <button
        type="button"
        @click="role = 'siswa'"
        :class="role === 'siswa' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
        class="flex-1 py-2.5 px-4 text-sm font-semibold rounded-lg transition-all cursor-pointer">
        <div class="flex items-center justify-center gap-2">
            <x-heroicon-o-academic-cap class="w-5 h-5" />
            Siswa
        </div>
    </button>
    <button
        type="button"
        @click="role = 'guru'"
        :class="role === 'guru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
        class="flex-1 py-2.5 px-4 text-sm font-semibold rounded-lg transition-all cursor-pointer">
        <div class="flex items-center justify-center gap-2">
            <x-heroicon-o-briefcase class="w-5 h-5" />
            Guru
        </div> 
    </button>
</div>