<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class BorrowingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $borrowings;

    public function __construct(Collection $borrowings)
    {
        $this->borrowings = $borrowings;
    }

    public function collection()
    {
        return $this->borrowings;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Peminjam',
            'No. Induk',
            'Daftar Alat (Qty)',
            'Tanggal Pinjam',
            'Tanggal Pengembalian',
            'Status',
            'Approved By',
            'Denda Final',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        static $no = 1;
        return [
            $no++,
            $item->peminjam->username ?? '-',
            $item->peminjam->no_induk ?? '-',
            $item->details->map(fn($d) => ($d->alat->nama ?? 'Alat Terhapus') . " ({$d->jumlah})")->implode(', '),
            $item->tgl_pinjam ? $item->tgl_pinjam->format('d/m/Y') : '-',
            $item->tgl_pengembalian ? $item->tgl_pengembalian->format('d/m/Y') : '-',
            strtoupper($item->status),
            $item->petugas->username ?? '-',
            'Rp ' . number_format($item->denda ?? 0, 0, ',', '.'),
            $item->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
