<?php

namespace App\Repositories\V1;

use App\Models\JobApplication;
use App\Repositories\DAO\V1\JobApplicationDAO;
use Illuminate\Database\Eloquent\Collection;

interface JobApplicationRepository
{
    public function findByUserIdAndJobPostId(int $userId, int $jobPostId): Collection;

    public function insert(JobApplicationDAO $dao): JobApplication;

    public function findByUserIdOrStatus(int $userId, ?string $status = null): Collection;

    public function findByJobPostIdOrStatus(int $jobPostId, ?string $status = null): Collection;
}
