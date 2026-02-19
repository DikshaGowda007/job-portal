<?php

namespace App\Repositories\MySql\V1;

use App\Models\JobPost;
use App\Repositories\DAO\V1\JobPostDAO;
use App\Repositories\V1\JobRepository;
use Carbon\Carbon;

class JobRepositoryImpl implements JobRepository
{
    public function insert(JobPostDAO $jobPostDAO): JobPost
    {
        $jobPostDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));
        $jobPostDAO->setUpdatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return JobPost::create($jobPostDAO->toArray());
    }
}
