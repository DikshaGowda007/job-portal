<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobSeekerProfile;
use App\Repositories\DAO\V1\JobSeekerProfileDAO;
use App\Repositories\V1\JobSeekerProfileRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class JobSeekerProfileRepositoryImpl implements JobSeekerProfileRepository
{
    public function findByUserIdWithExperiencesAndEducation(int $userId): Collection
    {
        return JobSeekerProfile::where('user_id', $userId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->with([
                'experiences' => fn ($q) => $q->where('is_deleted', CommonConstant::IS_DELETED_NO),
                'education' => fn ($q) => $q->where('is_deleted', CommonConstant::IS_DELETED_NO),
            ])
            ->get();
    }

    public function findByUserId(int $userId): Collection
    {
        return JobSeekerProfile::where('user_id', $userId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function insert(JobSeekerProfileDAO $jobSeekerProfileDAO): JobSeekerProfile
    {
        $jobSeekerProfileDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $jobSeekerProfileDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobSeekerProfile::create($jobSeekerProfileDAO->toArray());
    }

    public function updateByUserId(int $userId, JobSeekerProfileDAO $jobSeekerProfileDAO): bool|int
    {
        $jobSeekerProfileDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobSeekerProfile::where('user_id', $userId)
            ->update($jobSeekerProfileDAO->toArray());
    }
}
