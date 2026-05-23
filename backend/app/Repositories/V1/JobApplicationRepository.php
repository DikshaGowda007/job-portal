<?php

namespace App\Repositories\V1;

use App\Models\JobApplication;
use App\Repositories\DAO\V1\JobApplicationDAO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface JobApplicationRepository
{
    public function findByUserIdAndJobPostId(int $userId, int $jobPostId): Collection;

    public function insert(JobApplicationDAO $dao): JobApplication;

    public function findByUserIdOrStatus(int $userId, ?string $status = null): Collection;

    public function findByJobPostIdOrStatus(int $jobPostId, ?string $status = null): Collection;

    public function findByIdWithJobPostAndUser(int $id): Collection;

    public function updateById(int $id, JobApplicationDAO $jobApplicationDAO): bool|int;

    public function findByUserAndJob(int $userId, int $jobPostId): ?JobApplication;

    public function fetchByJobPost(int $jobPostId, ?string $status = null): Collection;

    public function fetchByUserIdAndStatus(int $userId, int $status): Collection;

    public function fetchByRecruiterWithFilters(int $recruiterId, array $filters): Collection;

    public function findAll(): Collection;

    public function findByCreatedAtRange(Carbon $startDate, Carbon $endDate): Collection;

    public function findByRecruiterId(int $recruiterId): Collection;

    public function findAllWithFilters(array $filters): Collection;
}
