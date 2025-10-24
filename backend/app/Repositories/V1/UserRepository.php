<?php

namespace App\Repositories\V1;

use App\Models\User;
use App\Repositories\DAO\V1\UserDAO;

interface UserRepository
{
    public function insert(UserDAO $userDAO): User;
}
