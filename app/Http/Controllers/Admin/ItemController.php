<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AlatExport;
use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;

use App\Models\Kategori;
use App\Http\Requests\Admin\ItemRequest;
use App\Services\ItemService;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AlatImport;

class ItemController extends Controller
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(Request $request)
    {
        $items = $this->itemService->getAllItems(5, $request->search);
        return view('admin.items.index', compact('items'));
    }

    public function export(Request $request)
    {
        $items = $this->itemService->exportItems($request->search);
        return Excel::download(new AlatExport($items), 'data-alat-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function create()
    {
        $categories = Kategori::all();
        return view('admin.items.create', compact('categories'));
    }

    public function store(ItemRequest $request)
    {
        $this->itemService->createItem($request->validated());

        return redirect()->route('admin.items.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    public function update(ItemRequest $request, Alat $item)
    {
        $updated = $this->itemService->updateItem($item, $request->validated());

        if (!$updated) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data alat.')
                ->withInput();
        }

        return redirect()->route('admin.items.index')
            ->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy(Alat $item)
    {
        $this->itemService->deleteItem($item);

        return redirect()->route('admin.items.index')
            ->with('success', 'Alat berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);

        try {
            $import = new AlatImport;
            Excel::import($import, $request->file('file'));

            $msg = "Data alat berhasil diimpor. ({$import->newCount} baru ditambahkan";
            if ($import->skippedCount > 0) {
                $msg .= ", {$import->skippedCount} data sudah ada dilewati";
            }
            $msg .= ").";

            return redirect()->route('admin.items.index')
                ->with('success', $msg);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Import selesai dengan beberapa peringatan: ';
            foreach ($failures as $failure) {
                $errorMsg .= "Baris {$failure->row()}: " . implode(', ', $failure->errors()) . ". ";
            }
            return back()->with('error', $errorMsg);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $header = [['nama', 'kategori', 'stok', 'keterangan']];
        return Excel::download(new class($header) implements \Maatwebsite\Excel\Concerns\FromCollection {
            protected $data;
            public function __construct($data)
            {
                $this->data = collect($data);
            }
            public function collection()
            {
                return $this->data;
            }
        }, 'template-import-alat.xlsx');
    }
}
