<?php

namespace App\Modules\V1\JobAlert\Helpers;

use App\Http\Requests\V1\JobAlert\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\JobAlert\Edit\DetailsRequest as EditDetailsRequest;
use App\Modules\V1\JobAlert\Bo\Add\DetailsBo as AddDetailsBo;
use App\Modules\V1\JobAlert\Bo\Edit\DetailsBo as EditDetailsBo;
use App\Repositories\DAO\V1\JobAlertDAO;
use App\Traits\V1\AccessRightsTrait;

class JobAlertHelper
{
    use AccessRightsTrait;

    public function __construct(
        private JobAlertDAO $jobAlertDao,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function prepareBo(AddDetailsRequest|EditDetailsRequest $detailsRequest): AddDetailsBo|EditDetailsBo
    {
        $detailsBo = null;

        if ($detailsRequest instanceof AddDetailsRequest) {
            $detailsBo = new AddDetailsBo;
            $detailsBo->setUserId($this->loggedInUserId);
        } elseif ($detailsRequest instanceof EditDetailsRequest) {
            $detailsBo = new EditDetailsBo;
        }

        if (! empty($detailsRequest->input('id'))) {
            $detailsBo->setId($detailsRequest->input('id'));
        }
        if (! empty($detailsRequest->input('keyword'))) {
            $detailsBo->setKeyword($detailsRequest->input('keyword'));
        }
        if (! empty($detailsRequest->input('location'))) {
            $detailsBo->setLocation($detailsRequest->input('location'));
        }
        if (! is_null($detailsRequest->input('job_category_id'))) {
            $detailsBo->setJobCategoryId($detailsRequest->input('job_category_id'));
        }
        if (! empty($detailsRequest->input('job_type'))) {
            $detailsBo->setJobType($detailsRequest->input('job_type'));
        }
        if (! empty($detailsRequest->input('work_mode'))) {
            $detailsBo->setWorkMode($detailsRequest->input('work_mode'));
        }
        if (! empty($detailsRequest->input('experience_level'))) {
            $detailsBo->setExperienceLevel($detailsRequest->input('experience_level'));
        }

        return $detailsBo;
    }

    public function prepareDao(AddDetailsBo|EditDetailsBo $detailsBo): JobAlertDAO
    {
        if (method_exists($detailsBo, 'getUserId') && ! empty($detailsBo->getUserId())) {
            $this->jobAlertDao->setUserId($detailsBo->getUserId());
        }
        if (! empty($detailsBo->getKeyword())) {
            $this->jobAlertDao->setKeyword($detailsBo->getKeyword());
        }
        if (! empty($detailsBo->getLocation())) {
            $this->jobAlertDao->setLocation($detailsBo->getLocation());
        }
        if (! empty($detailsBo->getJobCategoryId())) {
            $this->jobAlertDao->setJobCategoryId($detailsBo->getJobCategoryId());
        }
        if (! empty($detailsBo->getJobType())) {
            $this->jobAlertDao->setJobType($detailsBo->getJobType());
        }
        if (! empty($detailsBo->getWorkMode())) {
            $this->jobAlertDao->setWorkMode($detailsBo->getWorkMode());
        }
        if (! empty($detailsBo->getExperienceLevel())) {
            $this->jobAlertDao->setExperienceLevel($detailsBo->getExperienceLevel());
        }

        return $this->jobAlertDao;
    }
}
