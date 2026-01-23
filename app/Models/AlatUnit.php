<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Alat;

class AlatUnit extends Model
{
    use HasFactory;

    protected $table = 'alat_units';

    protected $fillable = [
        'alat_id',
        'unit_code',
        'status',
        'condition'
    ];

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
