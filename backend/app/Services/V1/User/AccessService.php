<?php

namespace App\Services\V1\User;

use App\Traits\V1\AccessRightsTrait;
use Illuminate\Support\Facades\Gate;

class AccessService
{
    use AccessRightsTrait;

    public function initializeUserAuth()
    {
        $this->initializeUserAuthorizationData();
    }

    public function hasJobPublishAccess()
    {
        return Gate::allows('job_publish', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }
}