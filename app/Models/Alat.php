<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
        'code',
        'kategori_id',
        'nama',
        'deskripsi',
        'stock',
        'gambar',
        'denda'
    ];

    protected $casts = [
        'stock' => 'integer',
        'denda' => 'decimal:2'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
