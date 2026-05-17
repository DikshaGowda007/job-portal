<?php

namespace App\Repositories\MySql\V1;

use App\Models\SavedJob;
use App\Repositories\DAO\V1\SavedJobDAO;
use App\Repositories\V1\SavedJobRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class SavedJobRepositoryImpl implements SavedJobRepository
{
    public function insert(SavedJobDAO $savedJobDAO): SavedJob
    {
        $savedJobDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return SavedJob::create($savedJobDAO->toArray());
    }

    public function fetchByUserId(int $userId): Collection
    {
        return SavedJob::where('user_id', $userId)
            ->where('is_deleted', 0)
            ->get();
    }

    public function updateById(int $id, SavedJobDAO $savedJobDao): bool|int
    {
        return SavedJob::where('id', $id)
            ->update($savedJobDao->toArray());
    }

    public function findByUserAndJob(int $userId, int $jobPostId): ?SavedJob
    {
        return SavedJob::where('user_id', $userId)
            ->where('job_post_id', $jobPostId)
            ->where('is_deleted', 0)
            ->first();
    }
}
