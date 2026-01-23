<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'peminjaman_id',
        'alat_id',
        'alat_unit_id',
        'jumlah',
        'denda_final',
        'keterangan'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    public function unit()
    {
        return $this->belongsTo(AlatUnit::class, 'alat_unit_id');
    }
}
