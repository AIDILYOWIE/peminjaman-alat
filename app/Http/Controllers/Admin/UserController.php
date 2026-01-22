<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers(5, $request->search);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function export(Request $request)
    {
        $users = $this->userService->exportUsers($request->search);
        return Excel::download(new \App\Exports\UsersExport($users), 'data-pengguna-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function store(UserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $updated = $this->userService->updateUser($user, $request->validated());

            if (!$updated) {
                return redirect()->back()
                    ->with('error', 'Gagal memperbarui data pengguna.')
                    ->withInput();
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
