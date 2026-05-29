<?php

namespace App\Modules\V1\JobSeekerProfile\Helpers;

use App\Http\Requests\V1\JobSeekerProfile\Experience\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\Experience\Update\DetailsRequest as UpdateDetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\Experience\Add\DetailsBo as AddDetailsBo;
use App\Modules\V1\JobSeekerProfile\Bo\Experience\Update\DetailsBo as UpdateDetailsBo;
use App\Repositories\DAO\V1\JobSeekerExperienceDAO;

class ExperienceHelper
{
    public function prepareBo(AddDetailsRequest|UpdateDetailsRequest $detailsRequest): AddDetailsBo|UpdateDetailsBo
    {
        $detailsBo = $detailsRequest instanceof AddDetailsRequest ? new AddDetailsBo : new UpdateDetailsBo;

        if ($detailsBo instanceof UpdateDetailsBo) {
            $detailsBo->setExperienceId($detailsRequest->input('experience_id'));
        }

        if (! empty($detailsRequest->input('job_title'))) {
            $detailsBo->setJobTitle($detailsRequest->input('job_title'));
        }
        if (! empty($detailsRequest->input('company_name'))) {
            $detailsBo->setCompanyName($detailsRequest->input('company_name'));
        }
        if (! empty($detailsRequest->input('employment_type'))) {
            $detailsBo->setEmploymentType($detailsRequest->input('employment_type'));
        }
        if (! empty($detailsRequest->input('location'))) {
            $detailsBo->setLocation($detailsRequest->input('location'));
        }
        if (! empty($detailsRequest->input('work_mode'))) {
            $detailsBo->setWorkMode($detailsRequest->input('work_mode'));
        }
        if (! empty($detailsRequest->input('start_date'))) {
            $detailsBo->setStartDate($detailsRequest->input('start_date'));
        }
        if (! empty($detailsRequest->input('end_date'))) {
            $detailsBo->setEndDate($detailsRequest->input('end_date'));
        }
        if (! is_null($detailsRequest->input('is_current'))) {
            $detailsBo->setIsCurrent((bool) $detailsRequest->input('is_current'));
        }
        if (! empty($detailsRequest->input('description'))) {
            $detailsBo->setDescription($detailsRequest->input('description'));
        }

        return $detailsBo;
    }

    public function prepareDao(AddDetailsBo|UpdateDetailsBo $detailsBo): JobSeekerExperienceDAO
    {
        $jobSeekerExperienceDao = new JobSeekerExperienceDAO;

        if (! is_null($detailsBo->getJobTitle())) {
            $jobSeekerExperienceDao->setJobTitle($detailsBo->getJobTitle());
        }
        if (! is_null($detailsBo->getCompanyName())) {
            $jobSeekerExperienceDao->setCompanyName($detailsBo->getCompanyName());
        }
        if (! is_null($detailsBo->getEmploymentType())) {
            $jobSeekerExperienceDao->setEmploymentType($detailsBo->getEmploymentType());
        }
        if (! is_null($detailsBo->getLocation())) {
            $jobSeekerExperienceDao->setLocation($detailsBo->getLocation());
        }
        if (! is_null($detailsBo->getWorkMode())) {
            $jobSeekerExperienceDao->setWorkMode($detailsBo->getWorkMode());
        }
        if (! is_null($detailsBo->getStartDate())) {
            $jobSeekerExperienceDao->setStartDate($detailsBo->getStartDate());
        }
        if (! is_null($detailsBo->getEndDate())) {
            $jobSeekerExperienceDao->setEndDate($detailsBo->getEndDate());
        }
        if (! is_null($detailsBo->getIsCurrent())) {
            $jobSeekerExperienceDao->setIsCurrent($detailsBo->getIsCurrent());
        }
        if (! is_null($detailsBo->getDescription())) {
            $jobSeekerExperienceDao->setDescription($detailsBo->getDescription());
        }

        return $jobSeekerExperienceDao;
    }
}
