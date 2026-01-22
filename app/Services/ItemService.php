<?php

namespace App\Services;

use App\Repositories\ItemRepository;
use App\Models\Alat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\LogService;

class ItemService
{
    protected $itemRepository;
    protected $cacheKey = 'items_all';

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Get all items, using cache if available.
     *
     * @param int $perPage
     * @param string|null $search
     * @param int|null $categoryId
     * @return LengthAwarePaginator
     */
    public function getAllItems(int $perPage = 5, ?string $search = null, ?int $categoryId = null): LengthAwarePaginator
    {
        return $this->itemRepository->getAllPaginated($perPage, $search, $categoryId);
    }

    public function exportItems(?string $search = null): \Illuminate\Support\Collection
    {
        return $this->itemRepository->getAllFiltered($search);
    }

    /**
     * Create a new item and clear cache.
     *
     * @param array $data
     * @return Alat
     */
    public function createItem(array $data): Alat
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $file = $data['gambar'] ?? null;

            // Temporary value for gambar because it's NOT NULL in database
            $data['gambar'] = 'pending';

            $item = $this->itemRepository->create($data);

            if ($file && $file instanceof \Illuminate\Http\UploadedFile) {
                // Store in folder with item id
                $path = $file->store("items/{$item->id}", 'public');
                $item->update(['gambar' => $path]);
            }

            $this->clearCache();
            LogService::log('CREATE', "Menambahkan alat baru: {$item->nama} ({$item->code})");
            return $item;
        });
    }

    /**
     * Update an item and clear cache.
     *
     * @param Alat $item
     * @param array $data
     * @return bool
     */
    public function updateItem(Alat $item, array $data): bool
    {
        if (isset($data['gambar']) && $data['gambar'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image
            if ($item->gambar) {
                Storage::disk('public')->delete($item->gambar);
            }
            // Store in folder with item id
            $data['gambar'] = $data['gambar']->store("items/{$item->id}", 'public');
        } else {
            // Avoid overwriting existing image with null
            unset($data['gambar']);
        }

        $updated = $this->itemRepository->update($item, $data);
        if ($updated) {
            $this->clearCache();
            LogService::log('UPDATE', "Memperbarui data alat: {$item->nama} ({$item->code})");
        }
        return $updated;
    }

    /**
     * Delete an item and clear cache.
     *
     * @param Alat $item
     * @return bool|null
     */
    public function deleteItem(Alat $item): ?bool
    {
        // Delete the entire folder for this item
        Storage::disk('public')->deleteDirectory("items/{$item->id}");

        $itemName = $item->nama;
        $itemCode = $item->code;
        $deleted = $this->itemRepository->delete($item);
        if ($deleted) {
            $this->clearCache();
            LogService::log('DELETE', "Menghapus alat: {$itemName} ({$itemCode})");
        }
        return $deleted;
    }

    /**
     * Clear item cache.
     */
    protected function clearCache(): void
    {
        Cache::forget($this->cacheKey);
        // Also clear category cache because counts might have changed
        Cache::forget('categories_all');
    }
}
