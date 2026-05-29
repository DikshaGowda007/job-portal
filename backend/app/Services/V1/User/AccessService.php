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

    public function hasApplicationWithdrawAccess()
    {
        return Gate::allows('application_withdraw', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasApplicationUpdateStatusAccess()
    {
        return Gate::allows('application_update_status', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasSavedJobAddAccess()
    {
        return Gate::allows('saved_job_add', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasSavedJobListAccess()
    {
        return Gate::allows('saved_job_list', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasSavedJobDeleteAccess()
    {
        return Gate::allows('saved_job_delete', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasJobCategoryViewAccess()
    {
        return Gate::allows('category_view', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasJobCategoryAddAccess()
    {
        return Gate::allows('category_add', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasJobCategoryEditAccess()
    {
        return Gate::allows('category_edit', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasJobCategoryDeleteAccess()
    {
        return Gate::allows('category_delete', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }

    public function hasUserEditAccess()
    {
        return Gate::allows('user_edit', [$this->loggedInUserAccessDetails, $this->loggedInUserRole]);
    }
}
