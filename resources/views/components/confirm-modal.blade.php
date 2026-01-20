<div
    x-data="{ 
        show: false, 
        isLoading: false,
        title: 'Konfirmasi',
        message: 'Apakah Anda yakin ingin melakukan tindakan ini?',
        onConfirm: null,
        cancelText: 'Batal',
        confirmText: 'Hapus',
        
        openConfirm(options) {
            this.title = options.title || 'Konfirmasi';
            this.message = options.message || '';
            this.confirmText = options.confirmText || 'Hapus';
            this.cancelText = options.cancelText || 'Batal';
            this.onConfirm = options.onConfirm;
            this.isLoading = false;
            this.show = true;
        },
        confirm() {
            this.isLoading = true;
            if (this.onConfirm) this.onConfirm();
            // Don't set show = false here, the page will reload or the caller will close it
        }
    }"
    x-on:open-confirm.window="openConfirm($event.detail)"
    x-show="show"
    class="fixed inset-0 z-[110] overflow-y-auto"
    style="display: none;">
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
        @click="show = false"></div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-100">
            <div class="bg-white p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center">
                        <x-heroicon-o-trash class="w-6 h-6 text-red-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900" x-text="title"></h3>
                        <p class="text-xs text-gray-500" x-text="message"></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row-reverse gap-2">
                    <button
                        @click="confirm()"
                        type="button"
                        :disabled="isLoading"
                        class="inline-flex w-full justify-center items-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-all active:scale-95 shadow-lg shadow-red-100 disabled:opacity-70 disabled:cursor-not-allowed min-w-[100px]">
                        <template x-if="!isLoading">
                            <span x-text="confirmText"></span>
                        </template>
                        <template x-if="isLoading">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Menghapus...</span>
                            </div>
                        </template>
                    </button>
                    <button
                        @click="show = false"
                        :disabled="isLoading"
                        type="button"
                        class="inline-flex w-full justify-center items-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl transition-all active:scale-95 disabled:opacity-50"
                        x-text="cancelText"></button>
                </div>
            </div>
        </div>
    </div>
</div>