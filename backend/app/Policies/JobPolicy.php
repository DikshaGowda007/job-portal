<?php

namespace App\Policies;

use App\Constants\AccessControlConstants;

class JobPolicy
{
    public function publish(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::JOB_PUBLISH] === 1;
    }
}
