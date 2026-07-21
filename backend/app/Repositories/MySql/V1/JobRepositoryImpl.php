<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Constants\JobConstants;
use App\Models\JobPost;
use App\Repositories\DAO\V1\JobPostDAO;
use App\Repositories\V1\JobRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class JobRepositoryImpl implements JobRepository
{
    public function insert(JobPostDAO $jobPostDAO): JobPost
    {
        $jobPostDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $jobPostDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobPost::create($jobPostDAO->toArray());
    }

    public function findById(int $id): Collection
    {
        return JobPost::where('id', $id)->get();
    }

    public function updateById(int $id, JobPostDAO $jobPostDAO): bool|int
    {
        $jobPostDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobPost::where('id', $id)
            ->update($jobPostDAO->toArray());
    }

    public function fetch(?string $data): Collection
    {
        return JobPost::where('is_deleted', CommonConstant::IS_DELETED_NO)->when($data, function ($query, $data) {
            $query->where('company_name', 'like', "%$data%")
                ->orWhere('title', 'like', "%$data%")
                ->orWhere('location', 'like', "%$data%")
                ->orWhere('salary', 'like', "%$data%")
                ->orWhere('salary_min', 'like', "%$data%")
                ->orWhere('salary_max', 'like', "%$data%")
                ->orWhere('salary_max', 'like', "%$data%")
                ->orWhere('salary_currency', 'like', "%$data%")
                ->orWhere('salary_type', 'like', "%$data%")
                ->orWhere('work_mode', 'like', "%$data%")
                ->orWhere('job_type', 'like', "%$data%")
                ->orWhere('experience_level', 'like', "%$data%")
                ->orWhere('experience_min', 'like', "%$data%")
                ->orWhere('experience_max', 'like', "%$data%")
                ->orWhere('education', 'like', "%$data%")
                ->orWhere('skills', 'like', "%$data%")
                ->orWhere('expires_at', 'like', "%$data%");
        })->get();
    }

    public function fetchWithFilters(array $filters): Collection
    {
        $query = JobPost::where('is_deleted', CommonConstant::IS_DELETED_NO);

        if (! empty($filters['text'])) {
            $searchText = $filters['text'];
            $query->where(function ($q) use ($searchText) {
                $q->where('company_name', 'like', "%$searchText%")
                    ->orWhere('title', 'like', "%$searchText%")
                    ->orWhere('location', 'like', "%$searchText%")
                    ->orWhere('education', 'like', "%$searchText%")
                    ->orWhere('skills', 'like', "%$searchText%");
            });
        }

        if (! empty($filters['work_mode'])) {
            $workModes = array_map('strtoupper', $filters['work_mode']);
            $query->whereIn('work_mode', $workModes);
        }

        if (! empty($filters['job_type'])) {
            $query->whereIn('job_type', $filters['job_type']);
        }

        if (! empty($filters['experience_level'])) {
            $query->whereIn('experience_level', $filters['experience_level']);
        }

        if (isset($filters['salary_min']) && $filters['salary_min'] !== null) {
            $query->where('salary_max', '>=', $filters['salary_min']);
        }
        if (isset($filters['salary_max']) && $filters['salary_max'] !== null) {
            $query->where('salary_min', '<=', $filters['salary_max']);
        }

        if (isset($filters['experience_min']) && $filters['experience_min'] !== null) {
            $query->where('experience_max', '>=', $filters['experience_min']);
        }
        if (isset($filters['experience_max']) && $filters['experience_max'] !== null) {
            $query->where('experience_min', '<=', $filters['experience_max']);
        }

        if (! empty($filters['location'])) {
            $query->where('location', 'like', '%'.$filters['location'].'%');
        }

        if (! empty($filters['job_category_id'])) {
            $query->where('job_category_id', $filters['job_category_id']);
        }

        if (! empty($filters['skills'])) {
            foreach ($filters['skills'] as $skill) {
                $query->where('skills', 'like', '%'.$skill.'%');
            }
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->get();
    }

    public function fetchByRecruiterWithFilters(int $recruiterId, array $filters): Collection
    {
        $query = JobPost::withCount(['applications' => fn ($q) => $q->where('is_deleted', CommonConstant::IS_DELETED_NO)])
            ->where('user_id', $recruiterId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO);

        if (! empty($filters['text'])) {
            $searchText = $filters['text'];
            $query->where(function ($q) use ($searchText) {
                $q->where('title', 'like', "%$searchText%")
                    ->orWhere('company_name', 'like', "%$searchText%")
                    ->orWhere('location', 'like', "%$searchText%")
                    ->orWhere('education', 'like', "%$searchText%")
                    ->orWhere('skills', 'like', "%$searchText%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['work_mode'])) {
            $workModes = array_map('strtoupper', $filters['work_mode']);
            $query->whereIn('work_mode', $workModes);
        }

        if (! empty($filters['job_type'])) {
            $query->whereIn('job_type', $filters['job_type']);
        }

        if (! empty($filters['experience_level'])) {
            $query->whereIn('experience_level', $filters['experience_level']);
        }

        if (isset($filters['salary_min']) && $filters['salary_min'] !== null) {
            $query->where('salary_max', '>=', $filters['salary_min']);
        }
        if (isset($filters['salary_max']) && $filters['salary_max'] !== null) {
            $query->where('salary_min', '<=', $filters['salary_max']);
        }

        if (! empty($filters['location'])) {
            $query->where('location', 'like', '%'.$filters['location'].'%');
        }

        if (! empty($filters['job_category_id'])) {
            $query->where('job_category_id', $filters['job_category_id']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->get();
    }

    public function findAll(): Collection
    {
        return JobPost::where('is_deleted', CommonConstant::IS_DELETED_NO)->get();
    }

    public function findByRecruiterId(int $recruiterId): Collection
    {
        return JobPost::where('user_id', $recruiterId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function fetchTitleSuggestions(?string $query, int $limit): SupportCollection
    {
        return JobPost::where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->where('status', JobConstants::STATUS_OPEN)
            ->whereNotNull('title')
            ->when($query, fn ($q) => $q->where('title', 'like', "%$query%"))
            ->distinct()
            ->orderBy('title')
            ->limit($limit)
            ->pluck('title');
    }

    public function fetchLocationSuggestions(?string $query, int $limit): SupportCollection
    {
        return JobPost::where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->where('status', JobConstants::STATUS_OPEN)
            ->whereNotNull('location')
            ->when($query, fn ($q) => $q->where('location', 'like', "%$query%"))
            ->distinct()
            ->orderBy('location')
            ->limit($limit)
            ->pluck('location');
    }
}
