<?php

namespace App\Modules\V1\JobSeekerProfile\Helpers;

use App\Http\Requests\V1\JobSeekerProfile\Education\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\JobSeekerProfile\Education\Update\DetailsRequest as UpdateDetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\Education\Add\DetailsBo as AddDetailsBo;
use App\Modules\V1\JobSeekerProfile\Bo\Education\Update\DetailsBo as UpdateDetailsBo;
use App\Repositories\DAO\V1\JobSeekerEducationDAO;

class EducationHelper
{
    public function prepareBo(AddDetailsRequest|UpdateDetailsRequest $detailsRequest): AddDetailsBo|UpdateDetailsBo
    {
        $detailsBo = $detailsRequest instanceof AddDetailsRequest ? new AddDetailsBo : new UpdateDetailsBo;

        if ($detailsBo instanceof UpdateDetailsBo) {
            $detailsBo->setEducationId($detailsRequest->input('education_id'));
        }

        if (! empty($detailsRequest->input('degree'))) {
            $detailsBo->setDegree($detailsRequest->input('degree'));
        }
        if (! empty($detailsRequest->input('institution'))) {
            $detailsBo->setInstitution($detailsRequest->input('institution'));
        }
        if (! empty($detailsRequest->input('field_of_study'))) {
            $detailsBo->setFieldOfStudy($detailsRequest->input('field_of_study'));
        }
        if (! empty($detailsRequest->input('location'))) {
            $detailsBo->setLocation($detailsRequest->input('location'));
        }
        if (! is_null($detailsRequest->input('start_year'))) {
            $detailsBo->setStartYear((int) $detailsRequest->input('start_year'));
        }
        if (! is_null($detailsRequest->input('end_year'))) {
            $detailsBo->setEndYear((int) $detailsRequest->input('end_year'));
        }
        if (! is_null($detailsRequest->input('is_current'))) {
            $detailsBo->setIsCurrent((bool) $detailsRequest->input('is_current'));
        }
        if (! empty($detailsRequest->input('description'))) {
            $detailsBo->setDescription($detailsRequest->input('description'));
        }

        return $detailsBo;
    }

    public function prepareDao(AddDetailsBo|UpdateDetailsBo $detailsBo): JobSeekerEducationDAO
    {
        $jobSeekerEducationDao = new JobSeekerEducationDAO;

        if (! is_null($detailsBo->getDegree())) {
            $jobSeekerEducationDao->setDegree($detailsBo->getDegree());
        }
        if (! is_null($detailsBo->getInstitution())) {
            $jobSeekerEducationDao->setInstitution($detailsBo->getInstitution());
        }
        if (! is_null($detailsBo->getFieldOfStudy())) {
            $jobSeekerEducationDao->setFieldOfStudy($detailsBo->getFieldOfStudy());
        }
        if (! is_null($detailsBo->getLocation())) {
            $jobSeekerEducationDao->setLocation($detailsBo->getLocation());
        }
        if (! is_null($detailsBo->getStartYear())) {
            $jobSeekerEducationDao->setStartYear($detailsBo->getStartYear());
        }
        if (! is_null($detailsBo->getEndYear())) {
            $jobSeekerEducationDao->setEndYear($detailsBo->getEndYear());
        }
        if (! is_null($detailsBo->getIsCurrent())) {
            $jobSeekerEducationDao->setIsCurrent($detailsBo->getIsCurrent());
        }
        if (! is_null($detailsBo->getDescription())) {
            $jobSeekerEducationDao->setDescription($detailsBo->getDescription());
        }

        return $jobSeekerEducationDao;
    }
}
