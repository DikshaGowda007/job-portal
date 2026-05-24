<?php

namespace App\Modules\V1\Recruiter\Helpers;

use App\Http\Requests\V1\Recruiter\MyApplications\DetailsRequest as MyApplicationsDetailsRequest;
use App\Http\Requests\V1\Recruiter\MyJobs\DetailsRequest as MyJobsDetailsRequest;
use App\Modules\V1\Recruiter\Bo\MyApplications\DetailsBo as MyApplicationsDetailsBo;
use App\Modules\V1\Recruiter\Bo\MyJobs\DetailsBo as MyJobsDetailsBo;
use App\Traits\V1\AccessRightsTrait;

class RecruiterHelper
{
    use AccessRightsTrait;

    public function __construct()
    {
        $this->initializeUserAuthorizationData();
    }

    public function prepareMyJobsBo(MyJobsDetailsRequest $myJobsDetailsRequest): MyJobsDetailsBo
    {
        $detailsBo = new MyJobsDetailsBo;

        $detailsBo->setText($myJobsDetailsRequest->input('text'));
        $detailsBo->setPage((int) $myJobsDetailsRequest->input('page', 1));
        $detailsBo->setPerPage((int) $myJobsDetailsRequest->input('per_page', 20));
        $detailsBo->setSortBy($myJobsDetailsRequest->input('sort_by', 'created_at'));
        $detailsBo->setSortOrder($myJobsDetailsRequest->input('sort_order', 'desc'));
        $detailsBo->setStatus($myJobsDetailsRequest->input('status'));
        $detailsBo->setWorkMode($myJobsDetailsRequest->input('work_mode'));
        $detailsBo->setJobType($myJobsDetailsRequest->input('job_type'));
        $detailsBo->setExperienceLevel($myJobsDetailsRequest->input('experience_level'));
        $detailsBo->setSalaryMin($myJobsDetailsRequest->input('salary_min') !== null ? (float) $myJobsDetailsRequest->input('salary_min') : null);
        $detailsBo->setSalaryMax($myJobsDetailsRequest->input('salary_max') !== null ? (float) $myJobsDetailsRequest->input('salary_max') : null);
        $detailsBo->setLocation($myJobsDetailsRequest->input('location'));
        $detailsBo->setJobCategoryId($myJobsDetailsRequest->input('job_category_id') !== null ? (int) $myJobsDetailsRequest->input('job_category_id') : null);

        return $detailsBo;
    }

    public function prepareMyApplicationsBo(MyApplicationsDetailsRequest $myApplicationsDetailsRequest): MyApplicationsDetailsBo
    {
        $detailsBo = new MyApplicationsDetailsBo;

        $detailsBo->setText($myApplicationsDetailsRequest->input('text'));
        $detailsBo->setJobPostId($myApplicationsDetailsRequest->input('job_post_id') !== null ? (int) $myApplicationsDetailsRequest->input('job_post_id') : null);
        $detailsBo->setStatus($myApplicationsDetailsRequest->input('status'));
        $detailsBo->setDateFrom($myApplicationsDetailsRequest->input('date_from'));
        $detailsBo->setDateTo($myApplicationsDetailsRequest->input('date_to'));
        $detailsBo->setPage((int) $myApplicationsDetailsRequest->input('page', 1));
        $detailsBo->setPerPage((int) $myApplicationsDetailsRequest->input('per_page', 20));
        $detailsBo->setSortBy($myApplicationsDetailsRequest->input('sort_by', 'job_applications.created_at'));
        $detailsBo->setSortOrder($myApplicationsDetailsRequest->input('sort_order', 'desc'));

        return $detailsBo;
    }
}
