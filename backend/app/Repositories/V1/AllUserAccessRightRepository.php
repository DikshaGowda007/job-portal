<?php

namespace App\Repositories\V1;

use App\Repositories\DAO\V1\AllUserAccessRightsDAO;
use Illuminate\Database\Eloquent\Collection;

interface AllUserAccessRightRepository
{
    public function findByUserId(int $userId): Collection;

    public function insert(AllUserAccessRightsDAO $allUserAccessRightsDAO);

    public function updateByUserId(int $userId, AllUserAccessRightsDAO $allUserAccessRightsDAO): bool|int;
}
