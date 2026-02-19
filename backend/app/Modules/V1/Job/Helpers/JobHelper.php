<?php

namespace App\Modules\V1\Job\Helpers;

use App\Modules\V1\Job\Bo\Add\DetailsBo as AddDetailsBo;
use App\Http\Requests\V1\Job\Add\DetailsRequest as AddDetailsRequest;
use App\Repositories\DAO\V1\JobPostDAO;
use App\Traits\V1\AccessRightsTrait;

class JobHelper
{
    use AccessRightsTrait;
    public function __construct(
        private JobPostDAO $jobPostDAO,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function prepareBo(AddDetailsRequest $detailsRequest): AddDetailsBo
    {
        $detailsBo = null;

        if ($detailsRequest instanceof AddDetailsRequest) { 
            $detailsBo = new AddDetailsBo();
        }

        if (!empty($detailsRequest->input('title'))) {
            $detailsBo->setTitle($detailsRequest->input('title'));
            }
        if (!empty($detailsRequest->input('company_name'))) {
            $detailsBo->setCompanyName($detailsRequest->input('company_name'));
        }
        if (!empty($detailsRequest->input('job_description'))) {
            $detailsBo->setJobDescription($detailsRequest->input('job_description'));
        }
        if (!empty($detailsRequest->input('location'))) {
            $detailsBo->setLocation($detailsRequest->input('location'));
        }
        if (!empty($detailsRequest->input('job_type'))) {
            $detailsBo->setJobType($detailsRequest->input('job_type'));
        }
        if (!empty($detailsRequest->input('work_mode'))) {
            $detailsBo->setWorkMode($detailsRequest->input('work_mode'));
        }
        if (!empty($detailsRequest->input('status'))) {
            $detailsBo->setStatus($detailsRequest->input('status'));
        }
        if (!empty($detailsRequest->input('experience_level'))) {
            $detailsBo->setExperienceLevel($detailsRequest->input('experience_level'));
        }
        if (!empty($detailsRequest->input('education'))) {
            $detailsBo->setEducation($detailsRequest->input('education'));
        }
        if (!is_null($detailsRequest->input('experience_min'))) {
            $detailsBo->setExperienceMin($detailsRequest->input('experience_min'));
        }
        if (!is_null($detailsRequest->input('experience_max'))) {
            $detailsBo->setExperienceMax($detailsRequest->input('experience_max'));
        }
        if (!is_null($detailsRequest->input('openings_count'))) {
            $detailsBo->setOpeningsCount($detailsRequest->input('openings_count'));
        }
        if (!is_null($detailsRequest->input('job_category_id'))) {
            $detailsBo->setJobCategoryId($detailsRequest->input('job_category_id'));
        }
        if (!is_null($detailsRequest->input('salary'))) {
            $detailsBo->setSalary($detailsRequest->input('salary'));
        }
        if (!is_null($detailsRequest->input('salary_min'))) {
            $detailsBo->setSalaryMin($detailsRequest->input('salary_min'));
        }
        if (!is_null($detailsRequest->input('salary_max'))) {
            $detailsBo->setSalaryMax($detailsRequest->input('salary_max'));
        }
        if (!empty($detailsRequest->input('salary_currency'))) {
            $detailsBo->setSalaryCurrency($detailsRequest->input('salary_currency'));
        }
        if (!empty($detailsRequest->input('salary_type'))) {
            $detailsBo->setSalaryType($detailsRequest->input('salary_type'));
        }
        if (is_array($detailsRequest->input('skills'))) {
            $detailsBo->setSkills(json_encode($detailsRequest->input('skills')));
        }
        if (is_array($detailsRequest->input('roles_responsibility'))) {
            $detailsBo->setRolesResponsibility(json_encode($detailsRequest->input('roles_responsibility')));
        }
        if (!empty($detailsRequest->input('expires_at'))) {
            $detailsBo->setExpiresAt($detailsRequest->input('expires_at'));
        }

        return $detailsBo;
    }

    public function prepareDAO(AddDetailsBo $detailsBo): JobPostDAO
    {
        $this->jobPostDAO->setUserId($this->loggedInUserId);
        $this->jobPostDAO->setCompanyName($detailsBo->getCompanyName());
        $this->jobPostDAO->setTitle($detailsBo->getTitle());
        $this->jobPostDAO->setJobDescription($detailsBo->getJobDescription());
        $this->jobPostDAO->setLocation($detailsBo->getLocation());

        $this->jobPostDAO->setSalary($detailsBo->getSalary());
        $this->jobPostDAO->setSalaryMin($detailsBo->getSalaryMin());
        $this->jobPostDAO->setSalaryMax($detailsBo->getSalaryMax());
        $this->jobPostDAO->setSalaryCurrency($detailsBo->getSalaryCurrency());
        $this->jobPostDAO->setSalaryType($detailsBo->getSalaryType());

        $this->jobPostDAO->setJobCategoryId($detailsBo->getJobCategoryId());
        $this->jobPostDAO->setWorkMode($detailsBo->getWorkMode());
        $this->jobPostDAO->setJobType($detailsBo->getJobType());

        $this->jobPostDAO->setRolesResponsibility($detailsBo->getRolesResponsibility());
        $this->jobPostDAO->setExperienceLevel($detailsBo->getExperienceLevel());
        $this->jobPostDAO->setExperienceMin($detailsBo->getExperienceMin());
        $this->jobPostDAO->setExperienceMax($detailsBo->getExperienceMax());
        $this->jobPostDAO->setEducation($detailsBo->getEducation());

        $this->jobPostDAO->setSkills($detailsBo->getSkills());
        $this->jobPostDAO->setStatus($detailsBo->getStatus());
        $this->jobPostDAO->setExpiresAt($detailsBo->getExpiresAt());
        $this->jobPostDAO->setOpeningsCount($detailsBo->getOpeningsCount());

        return $this->jobPostDAO;
    }
}