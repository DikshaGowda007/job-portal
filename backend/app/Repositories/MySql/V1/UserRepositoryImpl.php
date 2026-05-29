<?php

namespace App\Repositories\MySql\V1;

use App\Models\User;
use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\V1\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class UserRepositoryImpl implements UserRepository
{
    public function insert(UserDAO $userDao): User
    {
        $userDao->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $userDao->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return User::create($userDao->toArray());
    }

    public function updateById(int $userId, UserDAO $userDao): bool
    {
        $userDao->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return User::where('id', $userId)->update($userDao->toArray());
    }

    public function findByEmailAndPassword(string $email, string $password): Collection
    {
        return User::where('email', $email)->where('password', $password)->get();
    }

    public function findById(int $id): Collection
    {
        return User::where('id', $id)->get();
    }

    public function findByEmail(string $email): Collection
    {
        return User::where('email', $email)->get();
    }

    public function findByUserTypeOrStatusOrFirstNameOrLastNameOrEmail(array $filters): Collection
    {
        $query = User::query();

        if (! empty($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function findAll(): Collection
    {
        return User::get();
    }

    public function findByCreatedAt(Carbon $startDate, Carbon $endDate): Collection
    {
        return User::whereBetween('created_at', [$startDate, $endDate])->get();
    }
}
