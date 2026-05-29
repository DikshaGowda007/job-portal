<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Repositories\DAO\V1\JobApplicationDAO;
use App\Repositories\V1\JobApplicationRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class JobApplicationRepositoryImpl implements JobApplicationRepository
{
    public function findByUserIdAndJobPostId(int $userId, int $jobPostId): Collection
    {
        return JobApplication::where('user_id', $userId)
            ->where('job_post_id', $jobPostId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function insert(JobApplicationDAO $dao): JobApplication
    {
        $dao->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $dao->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobApplication::create($dao->toArray());
    }

    public function findByUserIdOrStatus(int $userId, ?string $status = null): Collection
    {
        $query = JobApplication::where('user_id', $userId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function findByJobPostIdOrStatus(int $jobPostId, ?string $status = null): Collection
    {
        $query = JobApplication::where('job_post_id', $jobPostId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function findByIdWithJobPostAndUser(int $id): Collection
    {
        return JobApplication::with(['jobPost', 'user'])
            ->where('id', $id)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function updateById(int $id, JobApplicationDAO $jobApplicationDAO): bool|int
    {
        $jobApplicationDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobApplication::where('id', $id)
            ->update($jobApplicationDAO->toArray());
    }

    public function findByUserAndJob(int $userId, int $jobPostId): ?JobApplication
    {
        return JobApplication::where('user_id', $userId)
            ->where('job_post_id', $jobPostId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->first();
    }

    public function fetchByJobPost(int $jobPostId, ?string $status = null): Collection
    {
        return JobApplication::where('job_post_id', $jobPostId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function fetchByUserIdAndStatus(int $userId, int $status): Collection
    {
        return JobApplication::where('user_id', $userId)
            ->where('status', $status)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function fetchByRecruiterWithFilters(int $recruiterId, array $filters): Collection
    {
        $query = JobApplication::join('job_posts', 'job_applications.job_post_id', '=', 'job_posts.id')
            ->join('users', 'job_applications.user_id', '=', 'users.id')
            ->where('job_posts.user_id', $recruiterId)
            ->where('job_posts.is_deleted', CommonConstant::IS_DELETED_NO)
            ->where('job_applications.is_deleted', CommonConstant::IS_DELETED_NO)
            ->select(
                'job_applications.*',
                'users.first_name',
                'users.last_name',
                'users.email',
                'job_posts.title as job_title',
                'job_posts.company_name'
            );

        if (! empty($filters['text'])) {
            $searchText = $filters['text'];
            $query->where(function ($q) use ($searchText) {
                $q->where('users.first_name', 'like', "%$searchText%")
                    ->orWhere('users.last_name', 'like', "%$searchText%")
                    ->orWhere('users.email', 'like', "%$searchText%")
                    ->orWhere('job_posts.title', 'like', "%$searchText%")
                    ->orWhere('job_posts.company_name', 'like', "%$searchText%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('job_applications.status', $filters['status']);
        }

        if (! empty($filters['job_post_id'])) {
            $query->where('job_applications.job_post_id', $filters['job_post_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->where('job_applications.created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->where('job_applications.created_at', '<=', $filters['date_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'job_applications.created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->get();
    }

    public function findAll(): Collection
    {
        return JobApplication::where('is_deleted', CommonConstant::IS_DELETED_NO)->get();
    }

    public function findByCreatedAtRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return JobApplication::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function findByRecruiterId(int $recruiterId): Collection
    {
        $jobIds = JobPost::where('user_id', $recruiterId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->pluck('id');

        return JobApplication::whereIn('job_post_id', $jobIds)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function findAllWithFilters(array $filters): Collection
    {
        $query = JobApplication::join('job_posts', 'job_applications.job_post_id', '=', 'job_posts.id')
            ->join('users as applicants', 'job_applications.user_id', '=', 'applicants.id')
            ->leftJoin('users as recruiters', 'job_posts.user_id', '=', 'recruiters.id')
            ->where('job_applications.is_deleted', CommonConstant::IS_DELETED_NO)
            ->select(
                'job_applications.*',
                'applicants.first_name',
                'applicants.last_name',
                'applicants.email',
                'job_posts.title as job_title',
                'job_posts.company_name',
                'job_posts.user_id as recruiter_user_id',
                'recruiters.first_name as recruiter_first_name',
                'recruiters.last_name as recruiter_last_name',
                'recruiters.email as recruiter_email',
            );

        if (! empty($filters['text'])) {
            $searchText = $filters['text'];
            $query->where(function ($q) use ($searchText) {
                $q->where('users.first_name', 'like', "%$searchText%")
                    ->orWhere('users.last_name', 'like', "%$searchText%")
                    ->orWhere('users.email', 'like', "%$searchText%")
                    ->orWhere('job_posts.title', 'like', "%$searchText%")
                    ->orWhere('job_posts.company_name', 'like', "%$searchText%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('job_applications.status', $filters['status']);
        }

        $query->orderBy('job_applications.created_at', 'desc');

        return $query->get();
    }
}
