<?php

namespace App\Services;

use App\Repositories\ItemRepository;
use App\Models\Alat;
use App\Models\AlatUnit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\LogService;
use App\Models\Kategori;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

    public function exportItems(?string $search = null): Collection
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
        return DB::transaction(function () use ($data) {
            $file = $data['gambar'] ?? null;
            $stock = $data['stock'] ?? 0;

            // 1. Internal SKU Generation if not manual
            if (empty($data['code'])) {
                $category = Kategori::find($data['kategori_id']);
                $catPrefix = Str::upper(Str::substr($category->nama ?? 'ALAT', 0, 3));

                // Smart Name Logic
                $words = explode(' ', trim($data['nama']));
                if (count($words) >= 2) {
                    $firstWord = Str::upper(Str::substr($words[0], 0, 2));
                    $lastWord = Str::upper(Str::substr(end($words), 0, 3));
                    $namePrefix = $firstWord . $lastWord;
                } else {
                    $namePrefix = Str::upper(Str::substr($data['nama'], 0, 5));
                }

                $skuBase = $catPrefix . '-' . $namePrefix;

                $sku = $skuBase;
                $counter = 1;
                while (Alat::where('code', $sku)->exists()) {
                    $sku = $skuBase . '-' . Str::random(3);
                    if ($counter++ > 5) break;
                }
                $data['code'] = $sku;
            }

            $data['gambar'] = 'pending';
            $item = $this->itemRepository->create($data);

            // 2. Create individual units (Asset Tracking)
            for ($i = 1; $i <= $stock; $i++) {
                $unitCode = $item->code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

                // Handle collision for unit codes
                while (AlatUnit::where('unit_code', $unitCode)->exists()) {
                    $unitCode = $item->code . '-' . str_pad($i + rand(100, 999), 3, '0', STR_PAD_LEFT);
                }

                AlatUnit::create([
                    'alat_id'   => $item->id,
                    'unit_code' => $unitCode,
                    'status'    => 'ready'
                ]);
            }

            if ($file && $file instanceof UploadedFile) {
                $path = $file->store("items/{$item->id}", 'public');
                $item->update(['gambar' => $path]);
            }

            $this->clearCache();
            LogService::log('CREATE', "Menambahkan alat baru: {$item->nama} ({$item->code}) dengan {$stock} unit.");
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
        if (isset($data['gambar']) && $data['gambar'] instanceof UploadedFile) {
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
