<div class="flex gap-2 p-1.5 bg-gray-100 rounded-xl">
    <button 
        type="button"
        @click="role = 'siswa'"
        :class="role === 'siswa' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
        class="flex-1 py-2.5 px-4 text-sm font-semibold rounded-lg transition-all cursor-pointer">
        <div class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
            </svg>
            Siswa
        </div>
    </button>
    <button 
        type="button"
        @click="role = 'guru'"
        :class="role === 'guru' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
        class="flex-1 py-2.5 px-4 text-sm font-semibold rounded-lg transition-all cursor-pointer">
        <div class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Guru
        </div>
    </button>
</div>
