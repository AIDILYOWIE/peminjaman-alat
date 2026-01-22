<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

class CategoryService
{
    protected $categoryRepository;
    protected $cacheKey = 'categories_all';

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories, paginated.
     *
     * @param int $perPage
     * @return mixed
     */
    public function getAllCategories(int $perPage = 5, ?string $search = null)
    {
        return $this->categoryRepository->getAllPaginated($perPage, $search);
    }

    public function exportCategories(?string $search = null): \Illuminate\Support\Collection
    {
        return $this->categoryRepository->getAllFiltered($search);
    }

    /**
     * Create a new category and clear cache.
     *
     * @param array $data
     * @return Kategori
     */
    public function createCategory(array $data): Kategori
    {
        $category = $this->categoryRepository->create($data);
        $this->clearCache();

        LogService::log('CREATE', "Menambahkan kategori baru: {$category->nama}");

        return $category;
    }

    /**
     * Update a category and clear cache.
     *
     * @param Kategori $category
     * @param array $data
     * @return bool
     */
    public function updateCategory(Kategori $category, array $data): bool
    {
        $oldName = $category->nama;
        $updated = $this->categoryRepository->update($category, $data);
        if ($updated) {
            $this->clearCache();
            LogService::log('UPDATE', "Memperbarui kategori dari '{$oldName}' menjadi '{$category->nama}'");
        }
        return $updated;
    }

    /**
     * Delete a category and clear cache.
     *
     * @param Kategori $category
     * @return bool|string
     */
    public function deleteCategory(Kategori $category)
    {
        if ($category->alat()->exists()) {
            return 'Kategori tidak dapat dihapus karena masih memiliki alat terkait.';
        }

        $categoryName = $category->nama;
        $deleted = $this->categoryRepository->delete($category);
        if ($deleted) {
            $this->clearCache();
            LogService::log('DELETE', "Menghapus kategori: {$categoryName}");
        }
        return true;
    }

    /**
     * Clear category cache.
     */
    protected function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }
}
