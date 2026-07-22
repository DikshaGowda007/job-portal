<?php

namespace App\Modules\V1\Recruiter\Helpers;

use App\Http\Requests\V1\Recruiter\CandidateSearch\DetailsRequest as CandidateSearchDetailsRequest;
use App\Http\Requests\V1\Recruiter\MyApplications\DetailsRequest as MyApplicationsDetailsRequest;
use App\Http\Requests\V1\Recruiter\MyJobs\DetailsRequest as MyJobsDetailsRequest;
use App\Modules\V1\Recruiter\Bo\CandidateSearch\DetailsBo as CandidateSearchDetailsBo;
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

    public function prepareCandidateSearchBo(CandidateSearchDetailsRequest $candidateSearchDetailsRequest): CandidateSearchDetailsBo
    {
        $detailsBo = new CandidateSearchDetailsBo;

        $detailsBo->setText($candidateSearchDetailsRequest->input('text'));
        $detailsBo->setSkills($candidateSearchDetailsRequest->input('skills'));
        $detailsBo->setLocation($candidateSearchDetailsRequest->input('location'));
        $detailsBo->setExperienceMin($candidateSearchDetailsRequest->input('experience_min') !== null ? (float) $candidateSearchDetailsRequest->input('experience_min') : null);
        $detailsBo->setExperienceMax($candidateSearchDetailsRequest->input('experience_max') !== null ? (float) $candidateSearchDetailsRequest->input('experience_max') : null);
        $detailsBo->setCurrentJobTitle($candidateSearchDetailsRequest->input('current_job_title'));
        $detailsBo->setWorkMode($candidateSearchDetailsRequest->input('work_mode'));
        $detailsBo->setJobType($candidateSearchDetailsRequest->input('job_type'));
        $detailsBo->setNoticePeriod($candidateSearchDetailsRequest->input('notice_period'));
        $detailsBo->setImmediateJoiner($candidateSearchDetailsRequest->input('immediate_joiner') !== null ? (bool) $candidateSearchDetailsRequest->input('immediate_joiner') : null);
        $detailsBo->setPage((int) $candidateSearchDetailsRequest->input('page', 1));
        $detailsBo->setPerPage((int) $candidateSearchDetailsRequest->input('per_page', 20));
        $detailsBo->setSortBy($candidateSearchDetailsRequest->input('sort_by', 'updated_at'));
        $detailsBo->setSortOrder($candidateSearchDetailsRequest->input('sort_order', 'desc'));

        return $detailsBo;
    }
}
