<?php

namespace App\Imports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class KategoriImport implements ToModel, WithHeadingRow, WithValidation
{
    public $newCount = 0;
    public $skippedCount = 0;

    public function model(array $row)
    {
        $nama = isset($row['nama']) ? trim((string)$row['nama']) : null;

        if (empty($nama)) {
            return null;
        }

        // Case-insensitive check for duplicates (PostgreSQL friendly)
        if (Kategori::whereRaw('LOWER(nama) = ?', [strtolower($nama)])->exists()) {
            $this->skippedCount++;
            return null;
        }

        $this->newCount++;
        return new Kategori([
            'nama' => $nama,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'nullable|string|max:255',
        ];
    }
}
