@props([
'open' => 'false',
'title' => 'Detail',
'isEditing' => 'false',
'onClose' => '',
'onToggleEdit' => '',
'onConfirm' => '',
'onCancel' => '',
])

<div x-show="{{ $open }}"
    class="fixed inset-0 z-50 overflow-hidden"
    style="display: none;">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-500/40 transition-opacity"
        @click="{{ $onClose }}"
        x-show="{{ $open }}"
        x-transition:enter="ease-in-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
        <div class="w-screen max-w-md"
            x-show="{{ $open }}"
            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            <div class="h-full flex flex-col bg-white shadow-xl overflow-y-auto">
                <!-- Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between sticky top-0 z-10">
                    <div class="flex-1 relative h-7 overflow-hidden">
                        <h2 class="text-lg font-bold text-gray-800 absolute inset-0"
                            x-show="!{{ $isEditing }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 opacity-0"
                            x-transition:enter-end="opacity-100 opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 opacity-100"
                            x-transition:leave-end="opacity-0 opacity-0">
                            Detail {{ $title }}
                        </h2>
                        <h2 class="text-lg font-bold text-gray-800 absolute inset-0"
                            x-show="{{ $isEditing }}"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 opacity-0"
                            x-transition:enter-end="opacity-100 opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 opacity-100"
                            x-transition:leave-end="opacity-0 opacity-0">
                            Edit {{ $title }}
                        </h2>
                    </div>
                    <div class="flex items-center sm:gap-2 sm:gap-1">
                        <!-- View Mode Actions -->
                        <div class="flex items-center gap-2"
                            x-show="!{{ $isEditing }}"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95">
                            <button @click="{{ $onToggleEdit }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 active:scale-90" title="Edit">
                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                            </button>
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 active:scale-90" title="Hapus">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Edit Mode Actions -->
                        <div class="flex items-center gap-2"
                            x-show="{{ $isEditing }}"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4">
                            <button @click="{{ $onConfirm }}" class="sm:px-4 sm:py-2 px-3 py-1.5 sm:text-sm text-xs font-semibold text-white bg-indigo-600 sm:rounded-xl rounded-lg hover:bg-indigo-700 transition-all duration-200 shadow-sm active:scale-95">
                                Konfirmasi
                            </button>
                            <button @click="{{ $onCancel }}" class="sm:px-4 sm:py-2 px-3 py-1.5 sm:text-sm text-xs font-semibold text-gray-700 bg-white border border-gray-200 sm:rounded-ll rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm active:scale-95">
                                Batal
                            </button>
                        </div>

                        <!-- Close Button -->
                        <button @click="{{ $onClose }}" class="ml-4 p-2 text-gray-400 hover:text-gray-500 transition-colors">
                            <x-heroicon-o-x-mark class="sm:w-6 sm:h-6 w-5 h-5" />
                        </button>
                    </div>
                </div>

                <!-- Content Slot -->
                <div class="relative flex-1">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>