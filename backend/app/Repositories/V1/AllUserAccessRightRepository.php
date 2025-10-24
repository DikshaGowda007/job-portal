<?php

namespace App\Repositories\V1;

use Illuminate\Database\Eloquent\Collection;

interface AllUserAccessRightRepository
{
    public function findByUserId(int $userId): Collection;
}
