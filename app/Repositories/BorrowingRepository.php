<?php

namespace App\Repositories;

use App\Models\Peminjaman;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BorrowingRepository
{
    /**
     * Get all borrowings with relationships and pagination.
     */
    public function getAllPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = Peminjaman::with(['peminjam', 'petugas', 'details.alat.kategori'])
            ->latest();

        if ($search) {
            $query->whereHas('peminjam', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('no_induk', 'like', "%{$search}%");
            })->orWhereHas('details.alat', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Find a borrowing by ID with details.
     */
    public function find(int $id): ?Peminjaman
    {
        return Peminjaman::with(['peminjam', 'petugas', 'details.alat.kategori'])->find($id);
    }

    /**
     * Create a new borrowing record.
     */
    public function create(array $data): Peminjaman
    {
        return Peminjaman::create($data);
    }

    /**
     * Update an existing borrowing record.
     */
    public function update(Peminjaman $peminjaman, array $data): bool
    {
        return $peminjaman->update($data);
    }

    /**
     * Delete a borrowing record.
     */
    public function delete(Peminjaman $peminjaman): bool
    {
        return $peminjaman->delete();
    }
}
