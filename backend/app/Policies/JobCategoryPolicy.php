<?php

namespace App\Policies;

use App\Constants\AccessControlConstants;

class JobCategoryPolicy
{
    public function view(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::CATEGORY_VIEW] === 1;
    }

    public function add(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::CATEGORY_ADD] === 1;
    }

    public function edit(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::CATEGORY_EDIT] === 1;
    }

    public function delete(?string $user, array $userAccessDetails, string $role): bool
    {
        return $userAccessDetails[AccessControlConstants::CATEGORY_DELETE] === 1;
    }
}
