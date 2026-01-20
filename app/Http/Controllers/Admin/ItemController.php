<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;

use App\Models\Kategori;
use App\Http\Requests\Admin\ItemRequest;
use App\Services\ItemService;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index()
    {
        $items = $this->itemService->getAllItems();
        return view('admin.items.index', compact('items'));
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
}
