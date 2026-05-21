<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobSeekerExperience;
use App\Repositories\DAO\V1\JobSeekerExperienceDAO;
use App\Repositories\V1\JobSeekerExperienceRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class JobSeekerExperienceRepositoryImpl implements JobSeekerExperienceRepository
{
    public function insert(JobSeekerExperienceDAO $jobSeekerExperienceDAO): JobSeekerExperience
    {
        $jobSeekerExperienceDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $jobSeekerExperienceDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobSeekerExperience::create($jobSeekerExperienceDAO->toArray());
    }

    public function findById(int $id): Collection
    {
        return JobSeekerExperience::where('id', $id)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function updateById(int $id, JobSeekerExperienceDAO $jobSeekerExperienceDAO): bool|int
    {
        $jobSeekerExperienceDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobSeekerExperience::where('id', $id)
            ->update($jobSeekerExperienceDAO->toArray());
    }
}
