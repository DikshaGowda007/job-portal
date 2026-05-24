<?php

namespace App\Repositories\V1;

use App\Models\JobCategory;
use App\Repositories\DAO\V1\JobCategoryDAO;
use Illuminate\Database\Eloquent\Collection;

interface JobCategoryRepository
{
    public function insert(JobCategoryDAO $jobCategoryDAO): JobCategory;

    public function findById(int $id): Collection;

    public function findBySlug(string $slug): Collection;

    public function updateById(int $id, JobCategoryDAO $jobCategoryDAO): bool|int;

    public function fetchWithJobCount(): Collection;
}
