<?php

namespace App\Imports;

use App\Models\Alat;
use App\Models\Kategori;
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

        // Duplicate check for Alat (Same name in same category)
        $exists = Alat::where('kategori_id', $kategori->id)
            ->whereRaw('LOWER(nama) = ?', [strtolower($nama)])
            ->exists();

        if ($exists) {
            $this->skippedCount++;
            return null;
        }

        $this->newCount++;

        // Generate code if missing
        $prefix = Str::upper(Str::substr($nama, 0, 2));
        $baseCode = $prefix . '-' . ($row['stok'] ?? 0);
        $code = $baseCode;

        // Collision avoidance: Ensure code is unique
        $counter = 1;
        while (Alat::where('code', $code)->exists()) {
            $code = $baseCode . '-' . Str::random(3);
            if ($counter++ > 5) break; // Safety break
        }

        return new Alat([
            'nama'        => $nama,
            'kategori_id' => $kategori->id,
            'code'        => $code,
            'stock'       => $row['stok'] ?? 0,
            'deskripsi'   => $row['keterangan'] ?? '-',
            'gambar'      => 'default-alat.png', // Mandatory field in migration
            'denda'       => 0,
        ]);
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
