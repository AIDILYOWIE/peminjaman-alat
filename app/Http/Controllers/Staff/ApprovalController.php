<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\BorrowingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    protected $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    /**
     * Display a listing of pending borrowings for staff approval.
     */
    public function index(Request $request)
    {
        // Get pending borrowings
        $borrowings = $this->borrowingService->getPaginatedPending(5, $request->search);

        // Stats for cards
        $stats = [
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'approved_today' => Peminjaman::where('status', 'dipinjam')
                ->whereDate('tgl_pinjam', now())
                ->count(),
            'rejected_today' => Peminjaman::where('status', 'ditolak')
                ->whereDate('updated_at', now())
                ->count(),
        ];

        // Transform for UI
        $borrowings->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->peminjam->username,
                'no_induk' => $item->peminjam->no_induk,
                'departemen' => 'Informasi Umum', // Placeholder as per prototype
                'tools_list' => $item->details->map(fn($d) => $d->unit ? $d->unit->unit_code : $d->alat->nama)->implode(', '),
                'tools' => $item->details->map(fn($d) => [
                    'name' => $d->alat->nama,
                    'unit_code' => $d->unit->unit_code ?? null,
                    'qty' => $d->jumlah
                ]),
                'qty' => $item->details->sum('jumlah'),
                'avatar' => 'heroicon-o-user',
                'status' => $item->status,
                'status_label' => ucfirst($item->status),
                'status_color' => $item->status === 'pending' ? 'yellow' : ($item->status === 'dipinjam' ? 'green' : 'red'),
                'borrow_date' => $item->tgl_pinjam ? $item->tgl_pinjam->format('Y-m-d') : now()->format('Y-m-d'),
                'return_date' => $item->tgl_pengembalian ? $item->tgl_pengembalian->format('Y-m-d') : '-',
                'duration' => $item->tgl_pinjam ? $item->tgl_pinjam->diffInDays($item->tgl_pengembalian) . ' Hari' : now()->diffInDays($item->tgl_pengembalian) . ' Hari',
                'email' => $item->peminjam->email ?? '-',
                'note' => $item->keterangan ?? '-',
                'category' => $item->details->first()->alat->kategori->nama ?? 'Alat',
            ];
        });

        if ($request->ajax()) {
            return response()->json($borrowings);
        }

        return view('staff.approvals.index', compact('borrowings', 'stats'));
    }

    /**
     * Update status of borrowing (Approve/Reject).
     */
    public function updateStatus(Request $request, Peminjaman $borrowing)
    {
        $request->validate([
            'status' => 'required|in:dipinjam,ditolak',
        ]);

        try {
            $this->borrowingService->updateStatus($borrowing, $request->status, Auth::id());

            $message = $request->status === 'dipinjam' ? 'Peminjaman telah disetujui.' : 'Peminjaman telah ditolak.';
            session()->flash('success', $message);
            return redirect()->route('staff.approvals.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui status: ' . $e->getMessage());
            return back();
        }
    }
}
