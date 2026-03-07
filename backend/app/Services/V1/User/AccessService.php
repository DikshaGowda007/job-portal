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

    public function hasJobEditAccess()
    {
        return Gate::allows('job_edit', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasJobDeleteAccess()
    {
        return Gate::allows('job_delete', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasJobApplyAccess()
    {
        return Gate::allows('job_apply', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasApplicationViewAccess()
    {
        return Gate::allows('application_view', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }
}
