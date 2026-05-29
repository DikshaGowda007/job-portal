<?php

namespace App\Modules\V1\JobSeekerProfile\Helpers;

use App\Http\Requests\V1\JobSeekerProfile\UploadResume\DetailsRequest;
use App\Modules\V1\JobSeekerProfile\Bo\UploadResume\DetailsBo;

class UploadResumeHelper
{
    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        $detailsBo = new DetailsBo;
        $detailsBo->setUploadedFile($detailsRequest->file('resume'));

        return $detailsBo;
    }
}
