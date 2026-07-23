<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobSeekerProfile;
use App\Repositories\DAO\V1\JobSeekerProfileDAO;
use App\Repositories\V1\JobSeekerProfileRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class JobSeekerProfileRepositoryImpl implements JobSeekerProfileRepository
{
    public function findByUserIdWithExperiencesAndEducation(int $userId): Collection
    {
        return JobSeekerProfile::where('user_id', $userId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->with([
                'experiences' => fn ($q) => $q->where('is_deleted', CommonConstant::IS_DELETED_NO),
                'education' => fn ($q) => $q->where('is_deleted', CommonConstant::IS_DELETED_NO),
            ])
            ->get();
    }

    public function findByUserId(int $userId): Collection
    {
        return JobSeekerProfile::where('user_id', $userId)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }

    public function insert(JobSeekerProfileDAO $jobSeekerProfileDAO): JobSeekerProfile
    {
        $jobSeekerProfileDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $jobSeekerProfileDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobSeekerProfile::create($jobSeekerProfileDAO->toArray());
    }

    public function updateByUserId(int $userId, JobSeekerProfileDAO $jobSeekerProfileDAO): bool|int
    {
        $jobSeekerProfileDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobSeekerProfile::where('user_id', $userId)
            ->update($jobSeekerProfileDAO->toArray());
    }

    public function searchPublicProfiles(array $filters): Collection
    {
        $query = JobSeekerProfile::where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->where('is_public', true)
            ->where('open_to_opportunities', true);

        if (! empty($filters['text'])) {
            $searchText = $filters['text'];
            $query->where(function ($q) use ($searchText) {
                $q->where('headline', 'like', "%$searchText%")
                    ->orWhere('summary', 'like', "%$searchText%")
                    ->orWhere('current_job_title', 'like', "%$searchText%")
                    ->orWhere('current_company', 'like', "%$searchText%")
                    ->orWhere('skills', 'like', "%$searchText%");
            });
        }

        if (! empty($filters['skills'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['skills'] as $skill) {
                    $q->orWhere('skills', 'like', '%"'.$skill.'"%');
                }
            });
        }

        if (! empty($filters['location'])) {
            $location = $filters['location'];
            $query->where(function ($q) use ($location) {
                $q->where('city', 'like', "%$location%")
                    ->orWhere('state', 'like', "%$location%")
                    ->orWhere('country', 'like', "%$location%");
            });
        }

        if (isset($filters['experience_min']) && $filters['experience_min'] !== null) {
            $query->where('total_experience_years', '>=', $filters['experience_min']);
        }
        if (isset($filters['experience_max']) && $filters['experience_max'] !== null) {
            $query->where('total_experience_years', '<=', $filters['experience_max']);
        }

        if (! empty($filters['current_job_title'])) {
            $query->where('current_job_title', 'like', '%'.$filters['current_job_title'].'%');
        }

        if (! empty($filters['work_mode'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['work_mode'] as $mode) {
                    $q->orWhere('preferred_work_modes', 'like', '%"'.$mode.'"%');
                }
            });
        }

        if (! empty($filters['job_type'])) {
            $query->where(function ($q) use ($filters) {
                foreach ($filters['job_type'] as $type) {
                    $q->orWhere('preferred_job_types', 'like', '%"'.$type.'"%');
                }
            });
        }

        if (! empty($filters['notice_period'])) {
            $query->where('notice_period', $filters['notice_period']);
        }

        if (array_key_exists('immediate_joiner', $filters) && $filters['immediate_joiner'] !== null) {
            $query->where('immediate_joiner', $filters['immediate_joiner']);
        }

        $sortBy = $filters['sort_by'] ?? 'updated_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->get();
    }

    public function findByUserIds(array $userIds): Collection
    {
        return JobSeekerProfile::whereIn('user_id', $userIds)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->get();
    }
}
