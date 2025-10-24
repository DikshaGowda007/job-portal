<?php

namespace App\Repositories\MySql\V1;

use App\Models\AllUserAccessRight;
use App\Repositories\DAO\V1\AllUserAccessRightsDAO;
use App\Repositories\V1\AllUserAccessRightRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AllUserAccessRightRepositoryImpl implements AllUserAccessRightRepository
{
    public function findByUserId(int $userId): Collection
    {
        return AllUserAccessRight::where('user_id', $userId)->get();
    }

    public function insert(AllUserAccessRightsDAO $allUserAccessRightsDAO)
    {
        $allUserAccessRightsDAO->setCreatedDate(Carbon::now()->format('Y-m-d H:i:s'));
        $allUserAccessRightsDAO->setModifiedDate(Carbon::now()->format('Y-m-d H:i:s'));
        return AllUserAccessRight::create($allUserAccessRightsDAO->toArray());
    }
}
