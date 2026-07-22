<?php

namespace App\Repositories\V1;

use App\Models\JobSeekerProfile;
use App\Repositories\DAO\V1\JobSeekerProfileDAO;
use Illuminate\Database\Eloquent\Collection;

interface JobSeekerProfileRepository
{
    public function findByUserIdWithExperiencesAndEducation(int $userId): Collection;

    public function findByUserId(int $userId): Collection;

    public function insert(JobSeekerProfileDAO $jobSeekerProfileDAO): JobSeekerProfile;

    public function updateByUserId(int $userId, JobSeekerProfileDAO $jobSeekerProfileDAO): bool|int;

    public function searchPublicProfiles(array $filters): Collection;
}
