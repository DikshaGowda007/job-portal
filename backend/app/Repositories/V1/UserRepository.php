<?php

namespace App\Repositories\V1;

use App\Models\User;
use App\Repositories\DAO\V1\UserDAO;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    public function insert(UserDAO $userDAO): User;
    
    public function updateById(int $userId, UserDAO $userDAO): bool;
    
    public function findByEmailAndPassword(string $email, string $password): Collection;

    public function findById(int $userId): Collection;
}
