<?php

namespace App\Modules\V1\JobApplication\Helpers;

use App\Constants\JobApplicationConstants;
use App\Http\Requests\V1\JobApplication\Apply\DetailsRequest as ApplyDetailsRequest;
use App\Http\Requests\V1\JobApplication\UpdateStatus\DetailsRequest as UpdateStatusDetailsRequest;
use App\Modules\V1\JobApplication\Bo\Apply\DetailsBo as ApplyDetailsBo;
use App\Modules\V1\JobApplication\Bo\UpdateStatus\DetailsBo as UpdateStatusDetailsBo;
use App\Repositories\DAO\V1\JobApplicationDAO;
use App\Traits\V1\AccessRightsTrait;

class JobApplicationHelper
{
    use AccessRightsTrait;
    public function __construct()
    {
        $this->initializeUserAuthorizationData();
    }

    public function prepareJobApplyBo(ApplyDetailsRequest $request): ApplyDetailsBo
    {
        $applyDetailsBo = new ApplyDetailsBo();

        $applyDetailsBo->setUserId($request->input('user_id'));
        $applyDetailsBo->setJobPostId($request->input('job_post_id'));
        $applyDetailsBo->setResumePath($request->input('resume_path'));
        $applyDetailsBo->setCoverLetter($request->input('cover_letter'));
        $applyDetailsBo->setExpectedSalary($request->input('expected_salary'));
        $applyDetailsBo->setExpectedSalaryCurrency($request->input('expected_salary_currency') ?? JobApplicationConstants::CURRENCY_INR);
        $applyDetailsBo->setNoticePeriod($request->input('notice_period'));
        $applyDetailsBo->setExperienceYears($request->input('experience_years'));

        return $applyDetailsBo;
    }

    public function prepareUpdateStatusBo(UpdateStatusDetailsRequest $updateStatusDetailsRequest): UpdateStatusDetailsBo
    {
        $updateStatusDetailsBo = new UpdateStatusDetailsBo;

        $updateStatusDetailsBo->setApplicationId($updateStatusDetailsRequest->input('application_id'));
        $updateStatusDetailsBo->setStatus($updateStatusDetailsRequest->input('status'));
        $updateStatusDetailsBo->setRecruiterNotes($updateStatusDetailsRequest->input('recruiter_notes'));

        return $updateStatusDetailsBo;
    }

    public function prepareJobApplyDAO(ApplyDetailsBo $applyDetailsBo): JobApplicationDAO
    {
        $jobApplicationDAO = new JobApplicationDAO();

        $jobApplicationDAO->setUserId($this->loggedInUserId);
        $jobApplicationDAO->setJobPostId($applyDetailsBo->getJobPostId());
        $jobApplicationDAO->setResumePath($applyDetailsBo->getResumePath());
        $jobApplicationDAO->setCoverLetter($applyDetailsBo->getCoverLetter());
        $jobApplicationDAO->setExpectedSalary($applyDetailsBo->getExpectedSalary());
        $jobApplicationDAO->setExpectedSalaryCurrency($applyDetailsBo->getExpectedSalaryCurrency());
        $jobApplicationDAO->setNoticePeriod($applyDetailsBo->getNoticePeriod());
        $jobApplicationDAO->setExperienceYears($applyDetailsBo->getExperienceYears());
        $jobApplicationDAO->setStatus(JobApplicationConstants::STATUS_PENDING);

        return $jobApplicationDAO;
    }
}
