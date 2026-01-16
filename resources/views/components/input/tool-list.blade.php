@props([
'label' => 'List Alat',
'tools' => [], // Array of tool options [id => name]
'name' => 'items'
])

<div class="space-y-4" x-data="{
    rows: [{ alat_id: '', jumlah: 1 }],
    addRow() {
        this.rows.push({ alat_id: '', jumlah: 1 });
    },
    removeRow(index) {
        if (this.rows.length > 1) {
            this.rows.splice(index, 1);
        }
    }
}">
    <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-4">
        <label class="block text-sm font-bold text-gray-800">
            {{ $label }}
        </label>
        <button
            type="button"
            @click="addRow()"
            class="cursor-pointer inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-700 py-1.5 px-3 bg-indigo-50 rounded-lg transition-colors">
            <x-heroicon-s-plus class="w-3.5 h-3.5" />
            Tambah Alat
        </button>
    </div>

    <div class="space-y-4">
        <template x-for="(row, index) in rows" :key="index">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-start bg-gray-50/50 p-4 rounded-xl border border-gray-100 relative group">
                {{-- Tool Selection --}}
                <div class="col-span-1 md:col-span-8">
                    <label :for="'alat_' + index" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 px-1">Nama Alat</label>
                    <div class="relative">
                        <select
                            :id="'alat_' + index"
                            :name=\"'{{ $name }}[' + index + '][alat_id]' \"
                            x-model="row.alat_id"
                            required
                            class="block w-full  border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 px-4 py-2.5 bg-gray-50 border">
                            <option value="" disabled selected>Pilih Alat...</option>
                            @foreach($tools as $id => $toolName)
                            <option value="{{ $id }}">{{ $toolName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Quantity --}}
                <div class="col-span-1 md:col-span-3">
                    <label :for="'qty_' + index" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 px-1 text-center">Jumlah</label>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="if(row.jumlah > 1) row.jumlah--"
                            class="cursor-pointer p-2.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                            <x-heroicon-o-minus class="w-4 h-4 text-gray-500" />
                        </button>
                        <input
                            :id="'qty_' + index"
                            type="number"
                            :name=\"'{{ $name }}[' + index + '][jumlah]' \"
                            x-model.number="row.jumlah"
                            min="1"
                            required
                            class="block w-full text-center border-gray-200 rounded-lg text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 py-2.5 bg-white border [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button
                            type="button"
                            @click="row.jumlah++"
                            class="cursor-pointer p-2.5 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                            <x-heroicon-s-plus class="w-4 h-4 text-gray-500" />
                        </button>
                    </div>
                </div>

                {{-- Action --}}
                <div class="absolute top-2 right-2 md:relative md:top-6 md:right-0 md:col-span-1 flex justify-end">
                    <button
                        type="button"
                        @click="removeRow(index)"
                        x-show="rows.length > 1"
                        class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                        title="Hapus">
                        <x-heroicon-o-trash class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Empty State --}}
    <div x-show="rows.length === 0" class="py-8 border-2 border-dashed border-gray-100 rounded-2xl flex flex-col items-center justify-center text-gray-400">
        <p class="text-xs font-medium">Belum ada alat yang ditambahkan</p>
    </div>
</div>