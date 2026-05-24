<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobCategory;
use App\Repositories\DAO\V1\JobCategoryDAO;
use App\Repositories\V1\JobCategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class JobCategoryRepositoryImpl implements JobCategoryRepository
{
    public function insert(JobCategoryDAO $jobCategoryDAO): JobCategory
    {
        return JobCategory::create($jobCategoryDAO->toArray());
    }

    public function findById(int $id): Collection
    {
        return JobCategory::where('id', $id)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function findBySlug(string $slug): Collection
    {
        return JobCategory::where('slug', $slug)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function updateById(int $id, JobCategoryDAO $jobCategoryDAO): bool|int
    {
        return JobCategory::where('id', $id)
            ->update($jobCategoryDAO->toArray());
    }

    public function fetchWithJobCount(): Collection
    {
        return JobCategory::where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->withCount(['jobs' => fn ($q) => $q->where('is_deleted', CommonConstant::IS_DELETED_NO)])
            ->orderBy('name', 'asc')
            ->get();
    }
}
