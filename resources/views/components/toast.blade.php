<div
    x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        showToast(msg, type = 'success') {
            this.message = msg;
            this.type = type;
            this.show = true;
            setTimeout(() => { this.show = false }, 3000);
        }
    }"
    x-init="
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @elseif(session('error'))
            showToast('{{ session('error') }}', 'error');
        @elseif($errors->any())
            showToast('Ada kesalahan pada input data Anda.', 'error');
        @endif
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="opacity-100 transform translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-5 right-5 z-[100] max-w-sm w-full bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 pointer-events-auto"
    style="display: none;">
    <div class="flex items-center gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
            :class="{
                'bg-indigo-100 text-indigo-600': type === 'success',
                'bg-red-100 text-red-600': type === 'error',
                'bg-blue-100 text-blue-600': type === 'info'
            }">
            <template x-if="type === 'success'">
                <x-heroicon-o-check-circle class="w-6 h-6" />
            </template>
            <template x-if="type === 'error'">
                <x-heroicon-o-x-circle class="w-6 h-6" />
            </template>
            <template x-if="type === 'info'">
                <x-heroicon-o-information-circle class="w-6 h-6" />
            </template>
        </div>

        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-gray-900" x-text="type.charAt(0).toUpperCase() + type.slice(1)"></p>
            <p class="text-xs text-gray-500 leading-relaxed" x-text="message"></p>
        </div>

        <button @click="show = false" class="flex-shrink-0 text-gray-400 hover:text-gray-500 transition-colors">
            <x-heroicon-o-x-mark class="w-5 h-5" />
        </button>
    </div>
</div>