<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'petugas_id',
        'tgl_pengembalian',
        'tgl_pinjam',
        'denda',
        'status',
        'keterangan',
        'invoice_code'
    ];

    protected $casts = [
        'tgl_pengembalian' => 'date',
        'tgl_pinjam' => 'date',
    ];

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function getSisaDurasi()
    {
        if (!$this->tgl_pengembalian) return 0;
        return (int) now()->startOfDay()->diffInDays($this->tgl_pengembalian->startOfDay(), false);
    }

    public function getTotalTarifDenda()
    {
        return $this->details->sum(function ($detail) {
            return ($detail->alat->denda ?? 0) * $detail->jumlah;
        });
    }

    protected static function booted()
    {
        static::creating(function ($peminjaman) {
            if (!$peminjaman->invoice_code) {
                $peminjaman->invoice_code = 'INV-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }
}
