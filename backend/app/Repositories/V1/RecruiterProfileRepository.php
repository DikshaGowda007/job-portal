<?php

namespace App\Repositories\V1;

use App\Models\RecruiterProfile;
use App\Repositories\DAO\V1\RecruiterProfileDAO;
use Illuminate\Database\Eloquent\Collection;

interface RecruiterProfileRepository
{
    public function findByUserId(int $userId): Collection;

    public function insert(RecruiterProfileDAO $recruiterProfileDAO): RecruiterProfile;

    public function updateByUserId(int $userId, RecruiterProfileDAO $recruiterProfileDAO): bool|int;
}
