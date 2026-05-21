<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobSeekerEducation;
use App\Repositories\DAO\V1\JobSeekerEducationDAO;
use App\Repositories\V1\JobSeekerEducationRepository;
use Carbon\Carbon;

class JobSeekerEducationRepositoryImpl implements JobSeekerEducationRepository
{
    public function insert(JobSeekerEducationDAO $jobSeekerEducationDAO): JobSeekerEducation
    {
        $jobSeekerEducationDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $jobSeekerEducationDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobSeekerEducation::create($jobSeekerEducationDAO->toArray());
    }

    public function findById(int $id): ?JobSeekerEducation
    {
        return JobSeekerEducation::where('id', $id)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->first();
    }

    public function updateById(int $id, JobSeekerEducationDAO $jobSeekerEducationDAO): bool|int
    {
        $jobSeekerEducationDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobSeekerEducation::where('id', $id)
            ->update($jobSeekerEducationDAO->toArray());
    }
}
