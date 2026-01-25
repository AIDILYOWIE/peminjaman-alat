<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $action = $request->action;

        $query = LogAktivitas::with('user')
            ->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('username', 'like', "%{$search}%");
                })->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($action) {
            $query->where('aksi', $action);
        }

        $logs = $query->paginate(5)->withQueryString();

        // Transform for UI
        $logs->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'username' => $item->user->username ?? 'System',
                'role' => $item->user->role ?? '-',
                'aksi' => $item->aksi,
                'status_color' => [
                    'CREATE' => 'green',
                    'UPDATE' => 'indigo',
                    'DELETE' => 'red'
                ][$item->aksi] ?? 'gray',
                'deskripsi' => $item->deskripsi,
                'created_at' => $item->created_at->format('d M Y, H:i'),
                'raw_date' => $item->created_at,
            ];
        });

        return view('admin.activity-logs.index', compact('logs'));
    }
}
