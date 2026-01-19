<?php

namespace App\Repositories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    /**
     * Get all categories with alat count.
     *
     * @return Collection
     */
    public function getAllWithCounts(): Collection
    {
        return Kategori::withCount('alat')->get();
    }

    /**
     * Find a category by ID.
     *
     * @param int|string $id
     * @return Kategori|null
     */
    public function find($id): ?Kategori
    {
        return Kategori::find($id);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Kategori
     */
    public function create(array $data): Kategori
    {
        return Kategori::create($data);
    }

    /**
     * Update an existing category.
     *
     * @param Kategori $category
     * @param array $data
     * @return bool
     */
    public function update(Kategori $category, array $data): bool
    {
        return $category->update($data);
    }

    /**
     * Delete a category.
     *
     * @param Kategori $category
     * @return bool|null
     */
    public function delete(Kategori $category): ?bool
    {
        return $category->delete();
    }
}
