<?php

namespace App\Http\Services;

use App\Constants\AccessControlConstants;

class AccessControlInitializer
{
    public function initializeAccess(): array
    {
        $userAccessDetails = [];

        $userAccessDetails[AccessControlConstants::JOB_VIEW] = 0;
        $userAccessDetails[AccessControlConstants::JOB_CREATE] = 0;
        $userAccessDetails[AccessControlConstants::JOB_EDIT] = 0;
        $userAccessDetails[AccessControlConstants::JOB_DELETE] = 0;
        $userAccessDetails[AccessControlConstants::JOB_PUBLISH] = 0;
        $userAccessDetails[AccessControlConstants::JOB_CLOSE] = 0;

        $userAccessDetails[AccessControlConstants::APPLICATION_VIEW] = 0;
        $userAccessDetails[AccessControlConstants::APPLICATION_SHORTLIST] = 0;
        $userAccessDetails[AccessControlConstants::APPLICATION_REJECT] = 0;
        $userAccessDetails[AccessControlConstants::APPLICATION_DOWNLOAD_RESUME] = 0;

        $userAccessDetails[AccessControlConstants::COMPANY_PROFILE_VIEW] = 0;
        $userAccessDetails[AccessControlConstants::COMPANY_PROFILE_EDIT] = 0;
        $userAccessDetails[AccessControlConstants::RECRUITER_MANAGE] = 0;

        $userAccessDetails[AccessControlConstants::CATEGORY_MANAGE] = 0;
        $userAccessDetails[AccessControlConstants::USER_MANAGE] = 0;
        $userAccessDetails[AccessControlConstants::ROLE_MANAGE] = 0;

        return $userAccessDetails;
    }
}
