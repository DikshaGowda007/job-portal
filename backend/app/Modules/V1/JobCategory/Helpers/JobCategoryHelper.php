<?php

namespace App\Modules\V1\JobCategory\Helpers;

use App\Constants\CommonConstant;
use App\Http\Requests\V1\JobCategory\Add\DetailsRequest as AddDetailsRequest;
use App\Http\Requests\V1\JobCategory\Edit\DetailsRequest as EditDetailsRequest;
use App\Modules\V1\JobCategory\Bo\Add\DetailsBo as AddDetailsBo;
use App\Modules\V1\JobCategory\Bo\Edit\DetailsBo as EditDetailsBo;
use App\Repositories\DAO\V1\JobCategoryDAO;
use Illuminate\Support\Str;

class JobCategoryHelper
{
    public function prepareAddJobCategoryBo(AddDetailsRequest $addDetailsRequest): AddDetailsBo
    {
        $addDetailsBo = new AddDetailsBo;

        $addDetailsBo->setName($addDetailsRequest->input('name'));
        $addDetailsBo->setSlug(Str::slug($addDetailsRequest->input('name')));

        return $addDetailsBo;
    }

    public function prepareEditJobCategoryBo(EditDetailsRequest $editDetailsRequest): EditDetailsBo
    {
        $editDetailsBo = new EditDetailsBo;

        $editDetailsBo->setId($editDetailsRequest->input('id'));
        $editDetailsBo->setName($editDetailsRequest->input('name'));
        $editDetailsBo->setSlug(Str::slug($editDetailsRequest->input('name')));

        if ($editDetailsRequest->has('status')) {
            $editDetailsBo->setStatus($editDetailsRequest->input('status'));
        }

        return $editDetailsBo;
    }

    public function prepareAddJobCategoryDao(AddDetailsBo $addDetailsBo): JobCategoryDAO
    {
        $jobCategoryDAO = new JobCategoryDAO;

        $jobCategoryDAO->setName($addDetailsBo->getName());
        $jobCategoryDAO->setSlug($addDetailsBo->getSlug());
        $jobCategoryDAO->setStatus(CommonConstant::STATUS_ACTIVE);

        return $jobCategoryDAO;
    }

    public function prepareEditJobCategoryDao(EditDetailsBo $editDetailsBo): JobCategoryDAO
    {
        $jobCategoryDAO = new JobCategoryDAO;

        $jobCategoryDAO->setName($editDetailsBo->getName());
        $jobCategoryDAO->setSlug($editDetailsBo->getSlug());

        if ($editDetailsBo->getStatus() !== null) {
            $jobCategoryDAO->setStatus($editDetailsBo->getStatus());
        }

        return $jobCategoryDAO;
    }
}
