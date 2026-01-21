<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
use App\Services\BorrowingService;
use App\Http\Requests\Admin\BorrowingRequest;
use Illuminate\Http\Request;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BorrowingExport;

class BorrowingController extends Controller
{
    protected $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    public function index(Request $request)
    {
        $borrowings = $this->borrowingService->getAllBorrowings(10, $request->search);

        // Transform for frontend standard
        $borrowings->getCollection()->transform(function ($item) {
            $statusData = $this->getStatusData($item->status);
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'name' => $item->peminjam->username,
                'no_induk' => $item->peminjam->no_induk,
                'role' => $item->peminjam->role,
                'tools' => $item->details->map(fn($d) => $d->alat->nama . " ({$d->jumlah})")->implode(', '),
                'qty' => $item->details->sum('jumlah'),
                'status' => $item->status,
                'status_label' => $statusData['label'],
                'status_color' => $statusData['color'],
                'remaining_duration' => (string) $item->getSisaDurasi(),
                'total_fine_rate' => $item->getTotalTarifDenda(),
                'return_date' => $item->tgl_pengembalian->format('d M Y'),
                'borrow_date' => $item->tgl_pinjam ? $item->tgl_pinjam->format('d M Y') : '-',
                'return_date_raw' => $item->tgl_pengembalian->format('Y-m-d'),
                'return_date_iso' => $item->tgl_pengembalian->format('Y-m-d\TH:i:s'),
                'borrow_date_raw' => $item->tgl_pinjam->format('Y-m-d'),
                'fine' => $item->denda ?? 0,
                'staff_name' => $item->petugas->username ?? '-',
                'email' => $item->peminjam->email ?? '-',
                'note' => $item->keterangan ?? '-',
                'category' => $item->details->first()?->alat->kategori->nama ?? '-',
                'details' => $item->details->map(fn($d) => [
                    'alat_id' => $d->alat_id,
                    'name' => $d->alat->nama,
                    'jumlah' => $d->jumlah,
                    'category' => $d->alat->kategori->nama ?? '-'
                ])
            ];
        });

        $items = Alat::all();

        return view('admin.borrowings.index', compact('borrowings', 'items'));
    }

    public function create()
    {
        $users = User::where('role', '!=', 'admin')->get();
        $items = Alat::where('stock', '>', 0)->get();
        return view('admin.borrowings.create', compact('users', 'items'));
    }

    public function store(BorrowingRequest $request)
    {
        try {
            $this->borrowingService->storeBorrowing($request->validated());
            return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil diajukan.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(BorrowingRequest $request, Peminjaman $borrowing)
    {
        try {
            $this->borrowingService->updateBorrowing($borrowing, $request->validated());
            return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Peminjaman $borrowing)
    {
        if ($borrowing->status === 'dipinjam' || $borrowing->status === 'selesai') {
            return back()->with('error', 'Peminjaman tidak dapat dihapus');
        }

        try {
            $this->borrowingService->deleteBorrowing($borrowing);
            return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil dihapus.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Peminjaman $borrowing)
    {
        $request->validate(['status' => 'required|in:dipinjam,selesai,ditolak']);

        try {
            $this->borrowingService->updateStatus($borrowing, $request->status);
            return back()->with('success', 'Status peminjaman berhasil diperbarui.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function getStatusData($status)
    {
        return match ($status) {
            'pending' => ['label' => 'Menunggu', 'color' => 'yellow'],
            'dipinjam' => ['label' => 'Dipinjam', 'color' => 'indigo'],
            'selesai' => ['label' => 'Selesai', 'color' => 'green'],
            'ditolak' => ['label' => 'Ditolak', 'color' => 'red'],
            default => ['label' => $status, 'color' => 'gray'],
        };
    }

    public function export(Request $request)
    {
        $borrowings = $this->borrowingService->exportBorrowings($request->search);
        return Excel::download(new BorrowingExport($borrowings), 'data-peminjaman-' . date('Y-m-d') . '.xlsx');
    }
}
