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

    public function withdraw(?string $user, array $userAccessDetails, string $role): bool
    {
        return $role == UserConstant::USER_ROLE_JOB_SEEKER && $userAccessDetails[AccessControlConstants::APPLICATION_WITHDRAW] === 1;
    }

    public function view(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::APPLICATION_VIEW] === 1;
    }

    public function status_update(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::APPLICATION_STATUS_UPDATE] === 1;
    }

    public function shortlist(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::APPLICATION_SHORTLIST] === 1;
    }

    public function reject(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::APPLICATION_REJECT] === 1;
    }
}
