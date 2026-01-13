<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'aksi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
