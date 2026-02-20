<?php

namespace App\Repositories\MySql\V1;

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
        return JobPost::where('id', $id)
            ->update($jobPostDAO->toArray());
    }
}
