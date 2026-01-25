<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Alat;
use App\Repositories\BorrowingRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Services\LogService;

use Illuminate\Support\Carbon;

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
    public function getActiveBorrowings(int $perPage = 10, ?string $search = null, bool $dueOnly = false): LengthAwarePaginator
    {
        return $this->borrowingRepository->getPaginatedFiltered($perPage, $search, 'dipinjam', $dueOnly);
    }

    /**
     * Get pending borrowings for staff approval.
     */
    public function getPaginatedPending(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->borrowingRepository->getPaginatedFiltered($perPage, $search, 'pending');
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
            $userId = $data['user_id'] ?? Auth::id();
            $borrowing = $this->borrowingRepository->create([
                'user_id' => $userId,
                'tgl_pengembalian' => $data['return_date'],
                'status' => 'pending',
                'tgl_pinjam' => $data['borrow_date'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // 2. Add details (multi-alat)
            foreach ($data['items'] as $item) {
                $alat = Alat::findOrFail($item['alat_id']);

                // Simple stock check (can be advanced)
                if ($alat->stock < $item['jumlah']) {
                    throw new Exception("Stok alat {$alat->nama} tidak mencukupi.");
                }

                // Create individual detail records for each unit (Asset Tracking)
                for ($i = 0; $i < $item['jumlah']; $i++) {
                    $borrowing->details()->create([
                        'alat_id' => $item['alat_id'],
                        'jumlah'  => 1, // Fixed to 1 for individual tracking
                    ]);
                }
            }

            if ($borrowing->peminjam->username) {
                LogService::log('CREATE', "Mengajukan peminjaman baru untuk: {$borrowing->peminjam->username}");
            } else {
                $username = Auth::user()->username ?? '#User';
                LogService::log('CREATE', "Mengajukan peminjaman baru untuk: {$username}");
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
                $updateFields['tgl_pengembalian'] = $data['return_date'];
            }
            if (isset($data['keterangan'])) {
                $updateFields['keterangan'] = $data['keterangan'];
            }

            if (!empty($updateFields)) {
                $peminjaman->update($updateFields);
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

            LogService::log('UPDATE', "Memperbarui data peminjaman ID #{$peminjaman->id}");
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

            if ($status === 'dipinjam' && $peminjaman->status !== 'dipinjam') {
                $updateData['petugas_id'] = $petugasId ?? Auth::id();
                $updateData['tgl_pinjam'] = now();

                // Logic to deduct stock when actually borrowed
                foreach ($peminjaman->details as $detail) {
                    $alat = $detail->alat;

                    if (!$alat) {
                        throw new Exception("Data alat untuk ID #{$detail->alat_id} tidak ditemukan.");
                    }

                    // Find an available unit for this specific tool
                    $unit = $alat->units()->where('status', 'ready')->lockForUpdate()->first();

                    if (!$unit) {
                        throw new Exception("Unit '{$alat->nama}' yang tersedia tidak mencukupi untuk disetujui (Kehabisan unit fisik).");
                    }

                    // Assign unit and change its status
                    $detail->update(['alat_unit_id' => $unit->id]);
                    $unit->update(['status' => 'borrowed']);

                    // Decrement stock in database (aggregate)
                    $alat->decrement('stock', 1);
                    $alat->refresh();
                }
            }

            if ($status === 'ditolak' && $peminjaman->status === 'dipinjam') {
                // If it was already approved but now rejected, restore stock
                foreach ($peminjaman->details as $detail) {
                    $detail->alat->increment('stock', $detail->jumlah);
                }
            }

            if ($status === 'selesai') {
                // Logic to restore stock when returned
                foreach ($peminjaman->details as $detail) {
                    // Release physical unit
                    if ($detail->alat_unit_id && $detail->unit) {
                        $detail->unit->update(['status' => 'ready']);
                    }

                    $detail->alat->increment('stock', 1);
                }

                // Final fine snapshot
                /** @var \Carbon\Carbon $deadline */
                $deadline = $peminjaman->tgl_pengembalian->startOfDay();
                /** @var \Carbon\Carbon $now */
                $now = now()->startOfDay();
                if ($now->greaterThan($deadline)) {
                    $diffDays = $now->diffInDays($deadline);
                    $updateData['denda'] = $diffDays * $peminjaman->getTotalTarifDenda();
                }
            }

            $updated = $this->borrowingRepository->update($peminjaman, $updateData);
            if ($updated) {
                LogService::log('UPDATE', "Mengubah status peminjaman ID #{$peminjaman->id} menjadi " . strtoupper($status));
            }
            return $updated;
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
            $borrowingId = $peminjaman->id;
            $deleted = $this->borrowingRepository->delete($peminjaman);
            if ($deleted) {
                LogService::log('DELETE', "Menghapus data peminjaman ID #{$borrowingId}");
            }
            return $deleted;
        });
    }

    public function rescheduleBorrowing(Peminjaman $borrowing, array $data): bool
    {
        $updated = $this->borrowingRepository->update($borrowing, [
            'tgl_pengembalian' => $data['tgl_pengembalian'],
            'keterangan' => $data['keterangan'] ?? $borrowing->keterangan,
        ]);
        if ($updated) {
            LogService::log('UPDATE', "Melakukan reschedule peminjaman ID #{$borrowing->id} ke tanggal {$data['tgl_pengembalian']}");
        }
        return $updated;
    }

    /**
     * Process a return from staff with detailed per-item fines and notes.
     */
    public function processStaffReturn(Peminjaman $peminjaman, array $data, ?int $petugasId = null): bool
    {
        return DB::transaction(function () use ($peminjaman, $data, $petugasId) {
            $petugasId = $petugasId ?? Auth::id();
            $totalAdditionalFine = 0;

            foreach ($data['details'] as $detailId => $detailData) {
                $detail = $peminjaman->details()->find($detailId);
                if ($detail) {
                    $itemFine = $detailData['denda_final'] ?? 0;
                    $detail->update([
                        'denda_final' => $itemFine,
                        'keterangan' => $detailData['keterangan'] ?? null,
                    ]);
                    $totalAdditionalFine += $itemFine;
                }
            }

            // Calculate standard late fine
            $lateFine = 0;
            $deadline = $peminjaman->tgl_pengembalian->startOfDay();
            $now = now()->startOfDay();
            if ($now->greaterThan($deadline)) {
                $diffDays = $now->diffInDays($deadline);
                $lateFine = $diffDays * $peminjaman->getTotalTarifDenda();
            }

            // Final status update with combined fine
            $updateData = [
                'status' => 'selesai',
                'petugas_id' => $petugasId,
                'denda' => $lateFine + $totalAdditionalFine,
            ];

            // Restore stock
            foreach ($peminjaman->details as $detail) {
                // Release physical unit
                if ($detail->alat_unit_id && $detail->unit) {
                    $detail->unit->update(['status' => 'ready']);
                }

                $detail->alat->increment('stock', 1);
            }

            $updated = $this->borrowingRepository->update($peminjaman, $updateData);
            if ($updated) {
                LogService::log('UPDATE', "Memproses pengembalian alat untuk peminjaman ID #{$peminjaman->id}");
            }
            return $updated;
        });
    }
}
