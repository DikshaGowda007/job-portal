<?php

namespace App\Repositories\V1;

use App\Models\JobSeekerEducation;
use App\Repositories\DAO\V1\JobSeekerEducationDAO;

interface JobSeekerEducationRepository
{
    public function insert(JobSeekerEducationDAO $jobSeekerEducationDAO): JobSeekerEducation;

    public function findById(int $id): ?JobSeekerEducation;

    public function updateById(int $id, JobSeekerEducationDAO $jobSeekerEducationDAO): bool|int;
}
