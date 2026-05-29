<?php

namespace App\Modules\V1\Job\Helpers;

use App\Http\Requests\V1\Job\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\Job\Edit\DetailsRequest as EditDetailsRequest;
use App\Http\Requests\V1\Job\Publish\DetailsRequest as PublishDetailsRequest;
use App\Modules\V1\Job\Bo\Add\DetailsBo as AddDetailsBo;
use App\Modules\V1\Job\Bo\Edit\DetailsBo as EditDetailsBo;
use App\Modules\V1\Job\Bo\Publish\DetailsBo as PublishDetailsBo;
use App\Repositories\DAO\V1\JobPostDAO;
use App\Traits\V1\AccessRightsTrait;

class JobHelper
{
    use AccessRightsTrait;

    public function __construct(
        private JobPostDAO $jobPostDao,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function prepareBo(AddDetailsRequest|EditDetailsRequest|PublishDetailsRequest $detailsRequest): AddDetailsBo|EditDetailsBo|PublishDetailsBo
    {
        $detailsBo = null;

        if ($detailsRequest instanceof AddDetailsRequest) {
            $detailsBo = new AddDetailsBo;
            $detailsBo->setUserId($this->loggedInUserId);
        } elseif ($detailsRequest instanceof EditDetailsRequest) {
            $detailsBo = new EditDetailsBo;
        } elseif ($detailsRequest instanceof PublishDetailsRequest) {
            $detailsBo = new PublishDetailsBo;
            $detailsBo->setModifiedByUserId($this->loggedInUserId);
        }

        if (! empty($detailsRequest->input('id'))) {
            $detailsBo->setId($detailsRequest->input('id'));
        }
        if (! empty($detailsRequest->input('title'))) {
            $detailsBo->setTitle($detailsRequest->input('title'));
        }
        if (! empty($detailsRequest->input('company_name'))) {
            $detailsBo->setCompanyName($detailsRequest->input('company_name'));
        }
        if (! empty($detailsRequest->input('job_description'))) {
            $detailsBo->setJobDescription($detailsRequest->input('job_description'));
        }
        if (! empty($detailsRequest->input('location'))) {
            $detailsBo->setLocation($detailsRequest->input('location'));
        }
        if (! empty($detailsRequest->input('job_type'))) {
            $detailsBo->setJobType($detailsRequest->input('job_type'));
        }
        if (! empty($detailsRequest->input('work_mode'))) {
            $detailsBo->setWorkMode($detailsRequest->input('work_mode'));
        }
        if (! empty($detailsRequest->input('status'))) {
            $detailsBo->setStatus($detailsRequest->input('status'));
        }
        if (! empty($detailsRequest->input('experience_level'))) {
            $detailsBo->setExperienceLevel($detailsRequest->input('experience_level'));
        }
        if (! empty($detailsRequest->input('education'))) {
            $detailsBo->setEducation($detailsRequest->input('education'));
        }
        if (! is_null($detailsRequest->input('experience_min'))) {
            $detailsBo->setExperienceMin($detailsRequest->input('experience_min'));
        }
        if (! is_null($detailsRequest->input('experience_max'))) {
            $detailsBo->setExperienceMax($detailsRequest->input('experience_max'));
        }
        if (! is_null($detailsRequest->input('openings_count'))) {
            $detailsBo->setOpeningsCount($detailsRequest->input('openings_count'));
        }
        if (! is_null($detailsRequest->input('job_category_id'))) {
            $detailsBo->setJobCategoryId($detailsRequest->input('job_category_id'));
        }
        if (! is_null($detailsRequest->input('salary'))) {
            $detailsBo->setSalary($detailsRequest->input('salary'));
        }
        if (! is_null($detailsRequest->input('salary_min'))) {
            $detailsBo->setSalaryMin($detailsRequest->input('salary_min'));
        }
        if (! is_null($detailsRequest->input('salary_max'))) {
            $detailsBo->setSalaryMax($detailsRequest->input('salary_max'));
        }
        if (! empty($detailsRequest->input('salary_currency'))) {
            $detailsBo->setSalaryCurrency($detailsRequest->input('salary_currency'));
        }
        if (! empty($detailsRequest->input('salary_type'))) {
            $detailsBo->setSalaryType($detailsRequest->input('salary_type'));
        }
        if (is_array($detailsRequest->input('skills'))) {
            $detailsBo->setSkills(json_encode($detailsRequest->input('skills')));
        }
        if (is_array($detailsRequest->input('roles_responsibility'))) {
            $detailsBo->setRolesResponsibility(json_encode($detailsRequest->input('roles_responsibility')));
        }
        if (! empty($detailsRequest->input('expires_at'))) {
            $detailsBo->setExpiresAt($detailsRequest->input('expires_at'));
        }

        return $detailsBo;
    }

    public function prepareDao(AddDetailsBo|EditDetailsBo|PublishDetailsBo $detailsBo): JobPostDAO
    {
        if (! empty($detailsBo->getUserId())) {
            $this->jobPostDao->setUserId($detailsBo->getUserId());
        } else {
            $this->jobPostDao->setUserId($this->loggedInUserId);
        }
        if (method_exists($detailsBo, 'getModifiedByUserId') && ! empty($detailsBo->getModifiedByUserId())) {
            $this->jobPostDao->setModifiedByUserId($detailsBo->getModifiedByUserId());
        }
        if (method_exists($detailsBo, 'getId') && ! empty($detailsBo->getId())) {
            $this->jobPostDao->setId($detailsBo->getId());
        }
        if (! empty($detailsBo->getCompanyName())) {
            $this->jobPostDao->setCompanyName($detailsBo->getCompanyName());
        }
        if (! empty($detailsBo->getTitle())) {
            $this->jobPostDao->setTitle($detailsBo->getTitle());
        }
        if (! empty($detailsBo->getJobDescription())) {
            $this->jobPostDao->setJobDescription($detailsBo->getJobDescription());
        }
        if (! empty($detailsBo->getLocation())) {
            $this->jobPostDao->setLocation($detailsBo->getLocation());
        }

        if (! empty($detailsBo->getSalary())) {
            $this->jobPostDao->setSalary($detailsBo->getSalary());
        }
        if (! empty($detailsBo->getSalaryMin())) {
            $this->jobPostDao->setSalaryMin($detailsBo->getSalaryMin());
        }
        if (! empty($detailsBo->getSalaryMax())) {
            $this->jobPostDao->setSalaryMax($detailsBo->getSalaryMax());
        }
        if (! empty($detailsBo->getSalaryCurrency())) {
            $this->jobPostDao->setSalaryCurrency($detailsBo->getSalaryCurrency());
        }
        if (! empty($detailsBo->getSalaryType())) {
            $this->jobPostDao->setSalaryType($detailsBo->getSalaryType());
        }

        if (! empty($detailsBo->getJobCategoryId())) {
            $this->jobPostDao->setJobCategoryId($detailsBo->getJobCategoryId());
        }
        if (! empty($detailsBo->getWorkMode())) {
            $this->jobPostDao->setWorkMode($detailsBo->getWorkMode());
        }
        if (! empty($detailsBo->getJobType())) {
            $this->jobPostDao->setJobType($detailsBo->getJobType());
        }

        if (! empty($detailsBo->getRolesResponsibility())) {
            $this->jobPostDao->setRolesResponsibility($detailsBo->getRolesResponsibility());
        }
        if (! empty($detailsBo->getExperienceLevel())) {
            $this->jobPostDao->setExperienceLevel($detailsBo->getExperienceLevel());
        }
        if (! empty($detailsBo->getExperienceMin())) {
            $this->jobPostDao->setExperienceMin($detailsBo->getExperienceMin());
        }
        if (! empty($detailsBo->getExperienceMax())) {
            $this->jobPostDao->setExperienceMax($detailsBo->getExperienceMax());
        }
        if (! empty($detailsBo->getEducation())) {
            $this->jobPostDao->setEducation($detailsBo->getEducation());
        }

        if (! empty($detailsBo->getSkills())) {
            $this->jobPostDao->setSkills($detailsBo->getSkills());
        }
        if (! empty($detailsBo->getStatus())) {
            $this->jobPostDao->setStatus($detailsBo->getStatus());
        }
        if (! empty($detailsBo->getExpiresAt())) {
            $this->jobPostDao->setExpiresAt($detailsBo->getExpiresAt());
        }
        if (! empty($detailsBo->getOpeningsCount())) {
            $this->jobPostDao->setOpeningsCount($detailsBo->getOpeningsCount());
        }

        return $this->jobPostDao;
    }
}
