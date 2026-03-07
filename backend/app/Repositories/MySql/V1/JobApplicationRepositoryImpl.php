<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobApplication;
use App\Repositories\DAO\V1\JobApplicationDAO;
use App\Repositories\V1\JobApplicationRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class JobApplicationRepositoryImpl implements JobApplicationRepository
{
    public function findByUserIdAndJobPostId(int $userId, int $jobPostId): Collection
    {
        return JobApplication::where('user_id', $userId)
            ->where('job_post_id', $jobPostId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function insert(JobApplicationDAO $dao): JobApplication
    {
        $dao->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $dao->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobApplication::create($dao->toArray());
    }
}
