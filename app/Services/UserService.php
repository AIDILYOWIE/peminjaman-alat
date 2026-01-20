<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
        return $user;
    }

    public function updateUser(User $user, array $data): bool
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $updated = $this->userRepository->update($user, $data);
        if ($updated) {
            $this->clearCache();
        }
        return $updated;
    }

    public function deleteUser(User $user): bool
    {
        $deleted = $this->userRepository->delete($user);
        if ($deleted) {
            $this->clearCache();
        }
        return $deleted;
    }

    protected function clearCache(): void
    {
        Cache::flush(); // Simple for now, can be optimized later to only clear user keys if needed
    }
}
