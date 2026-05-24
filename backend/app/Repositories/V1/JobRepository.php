<?php

namespace App\Repositories\V1;

use App\Models\JobPost;
use App\Repositories\DAO\V1\JobPostDAO;
use Illuminate\Database\Eloquent\Collection;

interface JobRepository
{
    public function insert(JobPostDAO $jobPostDAO): JobPost;

    public function findById(int $id): Collection;

    public function updateById(int $id, JobPostDAO $jobPostDAO): bool|int;

    public function fetch(?string $data): Collection;

    public function fetchByRecruiterWithFilters(int $recruiterId, array $filters): Collection;

    public function findAll(): Collection;

    public function findByRecruiterId(int $recruiterId): Collection;
}
