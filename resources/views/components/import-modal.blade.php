@props([
'title' => 'Import Data',
'action' => '#',
'description' => 'Unggah file Excel (.xlsx atau .csv) untuk mengimpor data.'
])

<div x-data="{ showImportModal: false, isSubmitting: false }"
    @trigger-import-modal.window="showImportModal = true; isSubmitting = false"
    x-show="showImportModal"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Backdrop --}}
        <div x-show="showImportModal"
            @click="showImportModal = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-gray-500/40 transition-opacity"
            aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal Content --}}
        <div x-show="showImportModal"
            @click.outside="showImportModal = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true">
                @csrf
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
                        <button type="button" @click="showImportModal = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <x-heroicon-o-x-mark class="w-6 h-6" />
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                            <p class="text-xs text-indigo-700 leading-relaxed">
                                {{ $description }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pilih File</label>
                            <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer border border-gray-200 rounded-xl p-1">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <button type="submit"
                        :disabled="isSubmitting"
                        class="cursor-pointer inline-flex justify-center items-center py-2 px-4 shadow-sm text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-all duration-200 active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed min-w-[120px]">
                        <span x-show="!isSubmitting">Import</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengimport...
                        </span>
                    </button>
                    <button type="button" @click="showImportModal = false" :disabled="isSubmitting" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>