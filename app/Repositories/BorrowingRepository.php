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
        return $this->applyFilters(Peminjaman::query(), $search)->paginate($perPage);
    }

    /**
     * Get all borrowings with relationships for export.
     */
    public function getAllFiltered(?string $search = null): Collection
    {
        return $this->applyFilters(Peminjaman::query(), $search)->get();
    }

    /**
     * Apply common filters for borrowings.
     */
    protected function applyFilters($query, ?string $search = null, ?string $status = null)
    {
        $query->with(['peminjam', 'petugas', 'details.alat.kategori'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('peminjam', function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('no_induk', 'like', "%{$search}%");
                })->orWhereHas('details.alat', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        return $query;
    }

    /**
     * Get paginated borrowings with optional status filter.
     */
    public function getPaginatedFiltered(int $perPage = 10, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        return $this->applyFilters(Peminjaman::query(), $search, $status)->paginate($perPage);
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
