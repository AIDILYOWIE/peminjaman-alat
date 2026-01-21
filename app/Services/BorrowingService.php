<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Alat;
use App\Repositories\BorrowingRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

use function Symfony\Component\Clock\now;

class BorrowingService
{
    protected $borrowingRepository;

    public function __construct(BorrowingRepository $borrowingRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
    }

    /**
     * Get all borrowings with pagination.
     */
    public function getAllBorrowings(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->borrowingRepository->getAllPaginated($perPage, $search);
    }

    /**
     * Get only returned borrowings (history).
     */
    public function getReturnsHistory(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->borrowingRepository->getPaginatedFiltered($perPage, $search, 'selesai');
    }

    /**
     * Get currently borrowed items available for return.
     */
    public function getActiveBorrowings(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->borrowingRepository->getPaginatedFiltered($perPage, $search, 'dipinjam');
    }

    /**
     * Get borrowings for export.
     */
    public function exportBorrowings(?string $search = null): \Illuminate\Support\Collection
    {
        return $this->borrowingRepository->getAllFiltered($search);
    }

    /**
     * Store a new borrowing transaction.
     */
    public function storeBorrowing(array $data): Peminjaman
    {
        return DB::transaction(function () use ($data) {
            // 1. Create main borrowing record
            $borrowing = $this->borrowingRepository->create([
                'user_id' => $data['user_id'],
                'tgl_pengembalian' => $data['return_date'],
                'status' => 'pending',
                'tgl_pinjam' => now(), // Filled on approval
            ]);

            // 2. Add details (multi-alat)
            foreach ($data['items'] as $item) {
                $alat = Alat::findOrFail($item['alat_id']);

                // Simple stock check (can be advanced)
                if ($alat->stock < $item['jumlah']) {
                    throw new Exception("Stok alat {$alat->nama} tidak mencukupi.");
                }

                $borrowing->details()->create([
                    'alat_id' => $item['alat_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            return $borrowing;
        });
    }

    /**
     * Update a borrowing transaction.
     */
    public function updateBorrowing(Peminjaman $peminjaman, array $data): bool
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            // Update main record
            if (isset($data['return_date'])) {
                $peminjaman->update(['tgl_pengembalian' => $data['return_date']]);
            }

            // Sync Details
            if (isset($data['items'])) {
                // If already borrowed, restore old stock first
                if ($peminjaman->status === 'dipinjam') {
                    foreach ($peminjaman->details as $detail) {
                        $detail->alat->increment('stock', $detail->jumlah);
                    }
                }

                // Delete old details
                $peminjaman->details()->delete();

                // Create new details
                foreach ($data['items'] as $item) {
                    $alat = Alat::findOrFail($item['alat_id']);

                    if ($peminjaman->status === 'dipinjam') {
                        if ($alat->stock < $item['jumlah']) {
                            throw new Exception("Stok alat {$alat->nama} tidak mencukupi.");
                        }
                        $alat->decrement('stock', $item['jumlah']);
                    }

                    $peminjaman->details()->create([
                        'alat_id' => $item['alat_id'],
                        'jumlah' => $item['jumlah'],
                    ]);
                }
            }

            return true;
        });
    }

    /**
     * Update borrowing status and handle consequences (stock, timestamps).
     */
    public function updateStatus(Peminjaman $peminjaman, string $status, ?int $petugasId = null): bool
    {
        return DB::transaction(function () use ($peminjaman, $status, $petugasId) {
            $updateData = ['status' => $status];

            if ($status === 'dipinjam') {
                $updateData['petugas_id'] = $petugasId ?? Auth::id();
                $updateData['tgl_pinjam'] = now();

                // Logic to deduct stock when actually borrowed
                foreach ($peminjaman->details as $detail) {
                    $alat = $detail->alat;
                    if ($alat->stock < $detail->jumlah) {
                        throw new Exception("Stok alat {$alat->nama} tidak mencukupi untuk disetujui.");
                    }
                    $alat->decrement('stock', $detail->jumlah);
                }
            }

            if ($status === 'selesai') {
                // Logic to restore stock when returned
                foreach ($peminjaman->details as $detail) {
                    $detail->alat->increment('stock', $detail->jumlah);
                }

                // Final fine snapshot
                /** @var \Carbon\Carbon $deadline */
                $deadline = $peminjaman->tgl_pengembalian->startOfDay();
                /** @var \Carbon\Carbon $now */
                $now = now();
                if ($now->greaterThan($deadline)) {
                    $diffMins = ceil($now->diffInMinutes($deadline));
                    $updateData['denda'] = $diffMins * $peminjaman->getTotalTarifDenda();
                }
            }

            return $this->borrowingRepository->update($peminjaman, $updateData);
        });
    }

    /**
     * Delete a borrowing transaction.
     */
    public function deleteBorrowing(Peminjaman $peminjaman): bool
    {
        return DB::transaction(function () use ($peminjaman) {
            // If already borrowed, should we restore stock? (Assuming policy)
            if ($peminjaman->status === 'dipinjam') {
                foreach ($peminjaman->details as $detail) {
                    $detail->alat->increment('stock', $detail->jumlah);
                }
            }
            return $this->borrowingRepository->delete($peminjaman);
        });
    }

    public function rescheduleBorrowing(Peminjaman $borrowing, array $data): bool
    {
        return $this->borrowingRepository->update($borrowing, [
            'tgl_pengembalian' => $data['tgl_pengembalian'],
            'keterangan' => $data['keterangan'] ?? $borrowing->keterangan,
        ]);
    }
}
