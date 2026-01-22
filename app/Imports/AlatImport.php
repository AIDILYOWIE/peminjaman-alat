<?php

namespace App\Imports;

use App\Models\Alat;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AlatImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Auto-create category if doesn't exist
        $kategori = Kategori::firstOrCreate([
            'nama' => $row['kategori']
        ]);

        return new Alat([
            'nama'        => $row['nama'],
            'kategori_id' => $kategori->id,
            'stock'       => $row['stok'] ?? 0,
            'keterangan'  => $row['keterangan'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'stok'     => 'nullable|numeric|min:0',
        ];
    }
}
