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
                    <h2 class="text-lg font-bold text-gray-800" x-text="{{ $isEditing }} ? 'Edit ' + '{{ $title }}' : 'Detail ' + '{{ $title }}'"></h2>
                    <div class="flex items-center gap-2">
                        <!-- View Mode Actions -->
                        <template x-if="!{{ $isEditing }}">
                            <div class="flex items-center gap-2">
                                <button @click="{{ $onToggleEdit }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </button>
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </template>

                        <!-- Edit Mode Actions -->
                        <template x-if="{{ $isEditing }}">
                            <div class="flex items-center gap-2">
                                <button @click="{{ $onConfirm }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                                    Konfirmasi
                                </button>
                                <button @click="{{ $onCancel }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                                    Batal
                                </button>
                            </div>
                        </template>

                        <!-- Close Button -->
                        <button @click="{{ $onClose }}" class="ml-4 p-2 text-gray-400 hover:text-gray-500 transition-colors">
                            <x-heroicon-o-x-mark class="w-6 h-6" />
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