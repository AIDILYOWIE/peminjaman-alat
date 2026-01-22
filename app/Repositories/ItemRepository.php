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
     * @param string|null $search
     * @param int|null $categoryId
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10, ?string $search = null, ?int $categoryId = null): LengthAwarePaginator
    {
        $query = Alat::with('kategori')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('kategori_id', $categoryId);
        }

        return $query->paginate($perPage);
    }

    public function getAllFiltered(?string $search = null): \Illuminate\Support\Collection
    {
        $query = Alat::with('kategori')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query->get();
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
