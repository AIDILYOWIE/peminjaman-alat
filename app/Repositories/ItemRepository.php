<?php

namespace App\Repositories;

use App\Models\Alat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemRepository
{
    /**
     * Get all items with categories, paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Alat::with('kategori')->latest()->paginate($perPage);
    }

    /**
     * Get all items with categories.
     *
     * @return Collection
     */
    public function getAllWithKategori(): Collection
    {
        return Alat::with('kategori')->latest()->get();
    }

    /**
     * Find an item by ID.
     *
     * @param int|string $id
     * @return Alat|null
     */
    public function find($id): ?Alat
    {
        return Alat::with('kategori')->find($id);
    }

    /**
     * Create a new item.
     *
     * @param array $data
     * @return Alat
     */
    public function create(array $data): Alat
    {
        return Alat::create($data);
    }

    /**
     * Update an existing item.
     *
     * @param Alat $item
     * @param array $data
     * @return bool
     */
    public function update(Alat $item, array $data): bool
    {
        return $item->update($data);
    }

    /**
     * Delete an item.
     *
     * @param Alat $item
     * @return bool|null
     */
    public function delete(Alat $item): ?bool
    {
        return $item->delete();
    }
}
