<?php

namespace App\Policies;

use App\Constants\AccessControlConstants;
use App\Constants\UserConstant;

class JobApplicationPolicy
{
    public function apply(?string $user, array $userAccessDetails, string $role): bool
    {
        return $role == UserConstant::USER_ROLE_JOB_SEEKER && $userAccessDetails[AccessControlConstants::JOB_APPLY];
    }
}
