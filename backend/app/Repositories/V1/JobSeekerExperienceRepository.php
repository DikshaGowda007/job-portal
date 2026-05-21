<?php

namespace App\Repositories\V1;

use App\Models\JobSeekerExperience;
use App\Repositories\DAO\V1\JobSeekerExperienceDAO;
use Illuminate\Database\Eloquent\Collection;

interface JobSeekerExperienceRepository
{
    public function insert(JobSeekerExperienceDAO $jobSeekerExperienceDAO): JobSeekerExperience;

    public function findById(int $id): Collection;

    public function updateById(int $id, JobSeekerExperienceDAO $jobSeekerExperienceDAO): bool|int;
}
