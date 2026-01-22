<?php

namespace App\Imports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class KategoriImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Case-insensitive check for duplicates
        if (Kategori::where('nama', $row['nama'])->exists()) {
            return null;
        }

        return new Kategori([
            'nama' => $row['nama'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
        ];
    }
}
