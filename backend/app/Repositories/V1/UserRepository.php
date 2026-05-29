<?php

namespace App\Repositories\V1;

use App\Models\User;
use App\Repositories\DAO\V1\UserDAO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    public function insert(UserDAO $userDao): User;

    public function updateById(int $userId, UserDAO $userDao): bool;

    public function findByEmailAndPassword(string $email, string $password): Collection;

    public function findById(int $id): Collection;

    public function findByEmail(string $email): Collection;

    public function findByUserTypeOrStatusOrFirstNameOrLastNameOrEmail(array $filters): Collection;

    public function findAll(): Collection;

    public function findByCreatedAt(Carbon $startDate, Carbon $endDate): Collection;
}
