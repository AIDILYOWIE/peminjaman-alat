<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\BorrowingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    /**
     * Display the unified transactions dashboard.
     */
    public function index(Request $request)
    {
        $currentTab = $request->query('tab', 'persetujuan');
        $search = $request->search;

        // Stats for tab badges
        $counts = [
            'persetujuan' => Peminjaman::where('status', 'pending')->count(),
            'pengembalian' => Peminjaman::where('status', 'dipinjam')->count(),
            'riwayat' => Peminjaman::where('status', 'selesai')->count(),
        ];

        // Fetch data based on tab
        if ($currentTab === 'persetujuan') {
            $borrowings = $this->borrowingService->getPaginatedPending(10, $search);
        } elseif ($currentTab === 'pengembalian') {
            $borrowings = $this->borrowingService->getActiveBorrowings(10, $search);
        } else {
            $borrowings = $this->borrowingService->getReturnsHistory(10, $search);
        }

        // Common Transformation for UI
        $borrowings->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->peminjam->username,
                'no_induk' => $item->peminjam->no_induk,
                'departemen' => 'Informasi Umum',
                'tools_list' => $item->details->map(fn($d) => $d->unit ? $d->unit->unit_code : $d->alat->nama)->implode(', '),
                'tools' => $item->details->map(fn($d) => [
                    'id' => $d->id,
                    'name' => $d->alat->nama,
                    'unit_code' => $d->unit->unit_code ?? null,
                    'qty' => $d->jumlah,
                    'fine_rate' => $d->alat->denda ?? 0,
                    'denda_final' => $d->denda_final ?? 0,
                    'keterangan' => $d->keterangan ?? ''
                ]),
                'qty' => $item->details->sum('jumlah'),
                'avatar' => 'heroicon-o-user',
                'status' => $item->status,
                'status_label' => ucfirst($item->status),
                'status_color' => $this->getStatusColor($item->status),
                'borrow_date' => $item->tgl_pinjam ? $item->tgl_pinjam->format('Y-m-d') : now()->format('Y-m-d'),
                'return_date' => $item->tgl_pengembalian->format('Y-m-d'),
                'return_date_iso' => $item->tgl_pengembalian->toIso8601String(),
                'fine' => $item->denda ?? 0,
                'email' => $item->peminjam->email ?? '-',
                'note' => $item->keterangan ?? '-',
                'category' => $item->details->first()->alat->kategori->nama ?? 'Alat',
            ];
        });

        if ($request->ajax()) {
            return response()->json($borrowings);
        }

        return view('staff.transactions.index', compact('borrowings', 'counts', 'currentTab'));
    }

    /**
     * Map status to Tailwind colors.
     */
    private function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'yellow',
            'dipinjam' => 'indigo',
            'selesai' => 'green',
            'ditolak' => 'red',
            default => 'gray',
        };
    }

    /**
     * Re-using existing approval logic.
     */
    public function approveRequest(Request $request, Peminjaman $borrowing)
    {
        $request->validate(['status' => 'required|in:dipinjam,ditolak']);
        try {
            $this->borrowingService->updateStatus($borrowing, $request->status, Auth::id());
            return response()->json(['success' => true, 'message' => 'Status peminjaman berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Re-using existing return logic.
     */
    public function processReturn(Request $request, Peminjaman $borrowing)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.denda_final' => 'required|numeric|min:0',
        ]);

        try {
            $this->borrowingService->processStaffReturn($borrowing, $request->only('details'), Auth::id());
            $borrowing->refresh(); // Refresh to get updated denda and status

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian berhasil diproses.',
                'id' => $borrowing->id,
                'fine' => $borrowing->denda,
                'tools' => $borrowing->details->map(fn($d) => [
                    'id' => $d->id,
                    'name' => $d->alat->nama,
                    'unit_code' => $d->unit->unit_code ?? null,
                    'qty' => $d->jumlah,
                    'fine_rate' => $d->alat->denda ?? 0,
                    'denda_final' => $d->denda_final ?? 0,
                    'keterangan' => $d->keterangan ?? ''
                ])
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Re-using invoice generation.
     */
    public function invoice(Peminjaman $borrowing)
    {
        return view('staff.returns.invoice', compact('borrowing'));
    }
}
