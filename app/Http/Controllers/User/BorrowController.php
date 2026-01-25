<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Services\ItemService;
use App\Http\Requests\User\StoreBorrowingRequest;
use App\Services\BorrowingService;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Cache;

class BorrowController extends Controller
{
    protected $itemService;
    protected $borrowingService;

    public function __construct(ItemService $itemService, BorrowingService $borrowingService)
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

        $items = Cache::remember($cacheKey, 60, function () use ($search, $category) {
            return $this->itemService->getAllItems(10, $search, $category);
        });

        // Cache categories generally
        $categories = Cache::remember('catalog_categories', 300, function () {
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

    public function store(StoreBorrowingRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            $this->borrowingService->storeBorrowing($data);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil diajukan!',
                'redirect' => route('user.borrow.history')
            ]);
        } catch (Exception $e) {
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
        $borrowings = Peminjaman::with(['details.alat', 'petugas'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.borrow.history', compact('borrowings'));
    }

    /**
     * Display digital invoice for a borrowing.
     */
    public function invoice(Peminjaman $borrowing)
    {
        // Ensure user owns this borrowing
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        $borrowing->load(['details.alat', 'peminjam', 'petugas']);
        return view('user.borrow.invoice', compact('borrowing'));
    }
}
