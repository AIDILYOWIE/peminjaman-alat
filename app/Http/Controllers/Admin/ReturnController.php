<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\BorrowingService;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    protected $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    /**
     * Display a listing of the returned borrowings.
     */
    public function index(Request $request)
    {
        $returns = $this->borrowingService->getActiveBorrowings(5, $request->search, true);

        $returns->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->peminjam->username,
                'no_induk' => $item->peminjam->no_induk,
                'tools' => $item->details->map(fn($d) => $d->alat->nama . " ({$d->jumlah})")->implode(', '),
                'qty' => $item->details->sum('jumlah'),
                'borrow_date' => $item->tgl_pinjam ? $item->tgl_pinjam->format('d M Y') : '-',
                'return_date' => $item->tgl_pengembalian->format('d M Y'),
                'fine' => $item->getSisaDurasi() < 0 ? abs($item->getSisaDurasi()) * $item->getTotalTarifDenda() : 0,
                'total_fine_rate' => $item->getTotalTarifDenda(),
                'return_date_iso' => $item->tgl_pengembalian->toIso8601String(),
                'staff_name' => $item->petugas->username ?? '-',
                'note' => $item->keterangan ?? '-',
            ];
        });

        if ($request->ajax()) {
            return response()->json($returns);
        }

        return view('admin.returns.index', compact('returns'));
    }

    /**
     * Show the form for creating a new return.
     * In this context, it displays active borrowings that can be returned.
     */
    public function create(Request $request)
    {
        $activeBorrowings = $this->borrowingService->getActiveBorrowings(5, $request->search, true);

        $activeBorrowings->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->peminjam->username,
                'no_induk' => $item->peminjam->no_induk,
                'tools' => $item->details->map(fn($d) => $d->alat->nama . " ({$d->jumlah})")->implode(', '),
                'borrow_date' => $item->tgl_pinjam ? $item->tgl_pinjam->format('d M Y') : '-',
                'return_date' => $item->tgl_pengembalian->format('d M Y'),
            ];
        });

        return response()->json($activeBorrowings);
    }

    /**
     * Store a newly created return in storage.
     * Effectively marks a borrowing as finished.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:peminjaman,id',
        ]);

        try {
            $borrowing = Peminjaman::findOrFail($request->borrowing_id);
            $this->borrowingService->updateStatus($borrowing, 'selesai');

            return redirect()->route('admin.returns.index')->with('success', 'Alat berhasil dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}
