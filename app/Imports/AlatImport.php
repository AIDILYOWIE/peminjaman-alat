<?php

namespace App\Imports;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\AlatUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class AlatImport implements ToModel, WithHeadingRow, WithValidation
{
    public $newCount = 0;
    public $skippedCount = 0;

    public function model(array $row)
    {
        $nama = isset($row['nama']) ? trim((string)$row['nama']) : null;
        $kategoriNama = isset($row['kategori']) ? trim((string)$row['kategori']) : null;

        if (empty($nama) || empty($kategoriNama)) {
            return null;
        }

        // Auto-create category if doesn't exist (Case-insensitive check)
        $kategori = Kategori::whereRaw('LOWER(nama) = ?', [strtolower($kategoriNama)])->first();

        if (!$kategori) {
            $kategori = Kategori::create([
                'nama' => $kategoriNama
            ]);
        }

        // Find or create Alat
        $alat = Alat::where('kategori_id', $kategori->id)
            ->whereRaw('LOWER(nama) = ?', [strtolower($nama)])
            ->first();

        if ($alat) {
            $this->skippedCount++; // Model exists, we might add units or skip
            $sku = $alat->code;
            $startCount = $alat->units()->count() + 1;
        } else {
            $this->newCount++;

            // Generate Alat SKU: [CATEGORY (3 chars)]-[NAME (3 chars)]
            $catPrefix = Str::upper(Str::substr($kategoriNama, 0, 3));
            $namePrefix = Str::upper(Str::substr($nama, 0, 3));
            $skuBase = $catPrefix . '-' . $namePrefix;

            $sku = $skuBase;
            $counter = 1;
            while (Alat::where('code', $sku)->exists()) {
                $sku = $skuBase . '-' . Str::random(3);
                if ($counter++ > 5) break;
            }

            $alat = Alat::create([
                'nama'        => $nama,
                'kategori_id' => $kategori->id,
                'code'        => $sku,
                'stock'       => 0, // Will be updated by unit creation
                'deskripsi'   => $row['keterangan'] ?? '-',
                'gambar'      => 'default-alat.png',
                'denda'       => 0,
            ]);
            $startCount = 1;
        }

        $stockToImport = intval($row['stok'] ?? 0);

        // Create individual units (Asset Tracking)
        for ($i = 0; $i < $stockToImport; $i++) {
            $sequence = $startCount + $i;
            $unitCode = $sku . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);

            // Handle collision for unit codes
            while (AlatUnit::where('unit_code', $unitCode)->exists()) {
                $sequence++;
                $unitCode = $sku . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
            }

            AlatUnit::create([
                'alat_id'   => $alat->id,
                'unit_code' => $unitCode,
                'status'    => 'ready'
            ]);
        }

        // Update total stock count in master table
        $alat->update(['stock' => $alat->units()->count()]);

        return null;
    }

    public function rules(): array
    {
        return [
            'nama'     => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'stok'     => 'nullable|numeric|min:0',
        ];
    }
}
