<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Services\ItemService;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    protected $itemService;
    protected $borrowingService;

    public function __construct(ItemService $itemService, \App\Services\BorrowingService $borrowingService)
    {
        $this->itemService = $itemService;
        $this->borrowingService = $borrowingService;
    }

    /**
     * Display the tool catalog for users.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $category = $request->category;
        $page = $request->get('page', 1);

        // Cache key based on fillers and page
        $cacheKey = "catalog_search_{$search}_cat_{$category}_page_{$page}";

        $items = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60, function () use ($search, $category) {
            return $this->itemService->getAllItems(10, $search, $category);
        });

        // Cache categories generally
        $categories = \Illuminate\Support\Facades\Cache::remember('catalog_categories', 300, function () {
            return Kategori::withCount('alat')->get();
        });

        return view('user.borrow.index', compact('items', 'categories'));
    }

    /**
     * Show the checkout page.
     */
    public function checkout()
    {
        return view('user.borrow.checkout');
    }

    /**
     * Store a new borrowing request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'return_date' => 'required|date|after_or_equal:today',
            'items' => 'required|array',
            'items.*.alat_id' => 'required|exists:alat,id',
            'items.*.qty' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        try {
            $formattedItems = array_map(function ($item) {
                return [
                    'alat_id' => $item['alat_id'],
                    'jumlah' => $item['qty']
                ];
            }, $request->items);

            $borrowing = $this->borrowingService->storeBorrowing([
                'user_id' => auth()->id(),
                'return_date' => $request->return_date,
                'items' => $formattedItems,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil diajukan!',
                'redirect' => route('user.borrow.history')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display user's borrowing history.
     */
    public function history()
    {
        $borrowings = \App\Models\Peminjaman::with(['details.alat', 'petugas'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.borrow.history', compact('borrowings'));
    }

    /**
     * Display digital invoice for a borrowing.
     */
    public function invoice(\App\Models\Peminjaman $borrowing)
    {
        // Ensure user owns this borrowing
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        $borrowing->load(['details.alat', 'peminjam', 'petugas']);
        return view('user.borrow.invoice', compact('borrowing'));
    }
}
