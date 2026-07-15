<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\RecruiterProfile;
use App\Repositories\DAO\V1\RecruiterProfileDAO;
use App\Repositories\V1\RecruiterProfileRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class RecruiterProfileRepositoryImpl implements RecruiterProfileRepository
{
    public function findByUserId(int $userId): Collection
    {
        return RecruiterProfile::where('user_id', $userId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function insert(RecruiterProfileDAO $recruiterProfileDAO): RecruiterProfile
    {
        $recruiterProfileDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $recruiterProfileDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return RecruiterProfile::create($recruiterProfileDAO->toArray());
    }

    public function updateByUserId(int $userId, RecruiterProfileDAO $recruiterProfileDAO): bool|int
    {
        $recruiterProfileDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return RecruiterProfile::where('user_id', $userId)
            ->update($recruiterProfileDAO->toArray());
    }
}
