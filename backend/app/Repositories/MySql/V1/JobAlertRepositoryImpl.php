<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobAlert;
use App\Repositories\DAO\V1\JobAlertDAO;
use App\Repositories\V1\JobAlertRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class JobAlertRepositoryImpl implements JobAlertRepository
{
    public function insert(JobAlertDAO $jobAlertDAO): JobAlert
    {
        $jobAlertDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobAlert::create($jobAlertDAO->toArray());
    }

    public function fetchByUserId(int $userId): Collection
    {
        return JobAlert::where('user_id', $userId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function findByIdAndUserId(int $id, int $userId): ?JobAlert
    {
        return JobAlert::where('id', $id)
            ->where('user_id', $userId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->first();
    }

    public function updateById(int $id, JobAlertDAO $jobAlertDAO): bool|int
    {
        $jobAlertDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobAlert::where('id', $id)
            ->update($jobAlertDAO->toArray());
    }

    public function findAllActive(): Collection
    {
        return JobAlert::where('is_active', 1)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }
}
