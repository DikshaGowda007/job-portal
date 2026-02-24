<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\JobPost;
use App\Repositories\DAO\V1\JobPostDAO;
use App\Repositories\V1\JobRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

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
}
