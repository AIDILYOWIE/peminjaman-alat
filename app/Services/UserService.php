<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Services\LogService;

class UserService
{
    protected $userRepository;
    protected $cacheKey = 'users_v2';

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(int $perPage = 5, ?string $search = null): LengthAwarePaginator
    {
        $page = request('page', 1);
        $cacheKey = "{$this->cacheKey}_page_{$page}_search_" . md5($search ?? '');

        return Cache::remember($cacheKey, 3600, function () use ($perPage, $search) {
            return $this->userRepository->getAllPaginated($perPage, $search);
        });
    }

    public function exportUsers(?string $search = null): Collection
    {
        return $this->userRepository->getAllFiltered($search);
    }

    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->userRepository->create($data);
        $this->clearCache();

        LogService::log('CREATE', "Menambahkan pengguna baru: {$user->username} ({$user->role})");

        return $user;
    }

    public function updateUser(User $user, array $data): bool
    {
        if (Auth::id() === $user->id) {
            throw new Exception("Admin tidak diperbolehkan mengedit profil mereka sendiri melalui halaman ini.");
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $updated = $this->userRepository->update($user, $data);
        if ($updated) {
            $this->clearCache();
            LogService::log('UPDATE', "Memperbarui data pengguna: {$user->username}");
        }
        return $updated;
    }

    public function deleteUser(User $user): bool
    {
        if (Auth::id() === $user->id) {
            throw new Exception("Anda tidak dapat menghapus akun Anda sendiri.");
        }

        // Check for active borrowings (pending or dipinjam)
        $activeBorrowings = \App\Models\Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($activeBorrowings) {
            throw new Exception("Pengguna tidak dapat dihapus karena memiliki data peminjaman yang masih aktif (Pending/Dipinjam).");
        }

        $username = $user->username;
        $deleted = $this->userRepository->delete($user);
        if ($deleted) {
            $this->clearCache();
            LogService::log('DELETE', "Menghapus pengguna: {$username}");
        }
        return $deleted;
    }

    protected function clearCache(): void
    {
        Cache::flush(); // Simple for now, can be optimized later to only clear user keys if needed
    }
}
