<div
    x-data="{ 
        show: false, 
        isLoading: false,
        title: 'Konfirmasi',
        message: 'Apakah Anda yakin ingin melakukan tindakan ini?',
        onConfirm: null,
        cancelText: 'Batal',
        confirmText: 'Konfirmasi',
        type: 'info', // info, danger, warning
        icon: 'heroicon-o-information-circle',
        
        openConfirm(options) {
            this.title = options.title || 'Konfirmasi';
            this.message = options.message || '';
            this.confirmText = options.confirmText || 'Konfirmasi';
            this.cancelText = options.cancelText || 'Batal';
            this.type = options.type || 'info';
            this.icon = options.icon || (this.type === 'danger' ? 'heroicon-o-trash' : 'heroicon-o-information-circle');
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
                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center transition-colors duration-300"
                        x-bind:class="{
                            'bg-red-50': type === 'danger',
                            'bg-indigo-50': type === 'info',
                            'bg-amber-50': type === 'warning'
                        }">
                        <!-- Conditional Icons to support dynamic feel without Blade runtime overhead -->
                        <div x-show="type === 'danger' && (icon === 'heroicon-o-trash' || !icon)"><x-heroicon-o-trash class="w-6 h-6 text-red-600" /></div>
                        <div x-show="type === 'danger' && icon === 'heroicon-o-x-circle'"><x-heroicon-o-x-circle class="w-6 h-6 text-red-600" /></div>

                        <div x-show="type === 'info' && (icon === 'heroicon-o-information-circle' || !icon)"><x-heroicon-o-information-circle class="w-6 h-6 text-indigo-600" /></div>
                        <div x-show="type === 'info' && icon === 'heroicon-o-check-badge'"><x-heroicon-o-check-badge class="w-6 h-6 text-indigo-600" /></div>
                        <div x-show="type === 'info' && icon === 'heroicon-m-check-badge'"><x-heroicon-m-check-badge class="w-6 h-6 text-indigo-600" /></div>

                        <div x-show="type === 'warning' && (icon === 'heroicon-o-exclamation-triangle' || !icon)"><x-heroicon-o-exclamation-triangle class="w-6 h-6 text-amber-600" /></div>

                        <!-- Fallback -->
                        <div x-show="!['danger', 'info', 'warning'].includes(type)"><x-heroicon-o-information-circle class="w-6 h-6 text-gray-400" /></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-900 truncate" x-text="title"></h3>
                        <p class="text-[12px] leading-relaxed text-gray-500" x-text="message"></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row-reverse gap-2">
                    <button
                        @click="confirm()"
                        type="button"
                        :disabled="isLoading"
                        class="inline-flex w-full justify-center items-center px-4 py-2.5 text-white text-sm font-bold rounded-xl transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed min-w-[100px] shadow-lg"
                        :class="{
                            'bg-red-600 hover:bg-red-700 shadow-red-100': type === 'danger',
                            'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-100': type === 'info',
                            'bg-amber-600 hover:bg-amber-700 shadow-amber-100': type === 'warning'
                        }">
                        <template x-if="!isLoading">
                            <span x-text="confirmText"></span>
                        </template>
                        <template x-if="isLoading">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="type === 'danger' ? 'Menghapus...' : 'Memproses...'"></span>
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