<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $categories;

    public function __construct(Collection $categories)
    {
        $this->categories = $categories;
    }

    public function collection()
    {
        return $this->categories;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Kategori',
            'Jumlah Alat',
            'Tanggal Dibuat',
        ];
    }

    public function map($category): array
    {
        static $no = 1;
        return [
            $no++,
            $category->nama,
            $category->alat_count ?? 0,
            $category->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
