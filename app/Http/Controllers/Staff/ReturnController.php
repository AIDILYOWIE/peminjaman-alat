<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\BorrowingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    protected $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    /**
     * Display a listing of currently borrowed items for return processing.
     */
    public function index(Request $request)
    {
        // Get active borrowings (status 'dipinjam') that are due today or overdue
        $borrowings = $this->borrowingService->getActiveBorrowings(5, $request->search, true);

        // Transform for UI
        $borrowings->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->peminjam->username,
                'no_induk' => $item->peminjam->no_induk,
                'departemen' => 'Informasi Umum', // Placeholder
                'tools_list' => $item->details->map(fn($d) => $d->alat->nama . " ({$d->jumlah})")->implode(', '),
                'tools' => $item->details->map(fn($d) => [
                    'id' => $d->id,
                    'name' => $d->alat->nama,
                    'qty' => $d->jumlah,
                    'fine_rate' => $d->alat->denda, // Current fine rate per item
                    'denda_final' => 0,
                    'keterangan' => ''
                ]),
                'qty' => $item->details->sum('jumlah'),
                'avatar' => 'heroicon-o-user',
                'status' => $item->status,
                'status_label' => ucfirst($item->status),
                'status_color' => 'indigo',
                'borrow_date' => $item->tgl_pinjam ? $item->tgl_pinjam->format('Y-m-d') : '-',
                'return_date' => $item->tgl_pengembalian->format('Y-m-d'),
                'return_date_iso' => $item->tgl_pengembalian->toIso8601String(),
                'fine' => $item->getSisaDurasi() < 0 ? abs($item->getSisaDurasi()) * $item->getTotalTarifDenda() : 0,
                'total_fine_rate' => $item->getTotalTarifDenda(),
                'email' => $item->peminjam->email ?? '-',
                'note' => $item->keterangan ?? '-',
                'category' => $item->details->first()->alat->kategori->nama ?? 'Alat',
            ];
        });

        if ($request->ajax()) {
            return response()->json($borrowings);
        }

        return view('staff.returns.index', compact('borrowings'));
    }

    /**
     * Process the return with itemized fines and final status.
     */
    public function approve(Request $request, Peminjaman $borrowing)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.denda_final' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string',
        ]);

        try {
            $this->borrowingService->processStaffReturn($borrowing, $request->only('details'), Auth::id());

            session()->flash('success', 'Pengembalian alat berhasil diproses dan diselesaikan.');
            return redirect()->route('staff.returns.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
            return back();
        }
    }
}
