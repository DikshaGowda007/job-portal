<?php

namespace App\Policies;

use App\Constants\AccessControlConstants;
use App\Constants\UserConstant;

class JobPolicy
{
    public function add(?string $user, array $userAccessDetails, string $role): bool
    {
        return $role !== UserConstant::USER_ROLE_JOB_SEEKER && $userAccessDetails[AccessControlConstants::JOB_EDIT] === 1;
    }

    public function publish(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::JOB_PUBLISH] === 1;
    }

    public function edit(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::JOB_EDIT] === 1;
    }

    public function delete(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::JOB_DELETE] === 1;
    }

    public function view(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::JOB_VIEW] === 1;
    }
}
