<?php

namespace App\Policies;

use App\Constants\AccessControlConstants;

class UserPolicy
{
    public function edit(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::USER_EDIT] === 1;
    }

    public function add(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::USER_ADD] === 1;
    }
}
