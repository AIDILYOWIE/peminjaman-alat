<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class LogService
{
    /**
     * Create a new activity log entry.
     *
     * @param string $aksi CREATE|UPDATE|DELETE
     * @param string $deskripsi
     * @param int|null $userId
     * @return LogAktivitas
     */
    public static function log(string $aksi, string $deskripsi, ?int $userId = null): LogAktivitas
    {
        return LogAktivitas::create([
            'user_id' => $userId ?? Auth::id(),
            'aksi' => strtoupper($aksi),
            'deskripsi' => $deskripsi,
            'created_at' => now(),
        ]);
    }
}
