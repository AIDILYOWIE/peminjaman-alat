<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class AlatExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Kode Alat',
            'Nama Alat',
            'Kategori',
            'Stok',
            'Denda per Hari',
            'Tanggal Dibuat',
        ];
    }

    public function map($item): array
    {
        static $no = 1;
        return [
            $no++,
            $item->code,
            $item->nama,
            $item->kategori->nama ?? '-',
            $item->stock,
            'Rp ' . number_format($item->denda, 0, ',', '.'),
            $item->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
