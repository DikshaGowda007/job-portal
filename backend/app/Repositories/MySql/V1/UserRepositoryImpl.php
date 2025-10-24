<?php

namespace App\Repositories\MySql\V1;

use App\Models\User;
use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\V1\UserRepository;
use Carbon\Carbon;

class UserRepositoryImpl implements UserRepository
{
    public function insert(UserDAO $userDAO): User
    {
        $userDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        return User::create($userDAO->toArray());
    }

    public function updateById(int $userId, UserDAO $userDAO): bool
    {
        $userDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        return User::where('id', $userId)->update($userDAO->toArray());
    }
}
