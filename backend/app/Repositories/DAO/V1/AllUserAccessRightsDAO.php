<?php

namespace App\Repositories\DAO\V1;

class AllUserAccessRightsDAO
{
    private ?int $userId = null;

    // Job Management
    private ?int $jobView = null;
    private ?int $jobCreate = null;
    private ?int $jobEdit = null;
    private ?int $jobDelete = null;
    private ?int $jobPublish = null;
    private ?int $jobClose = null;

    // Application Management
    private ?int $applicationView = null;
    private ?int $applicationShortlist = null;
    private ?int $applicationReject = null;
    private ?int $applicationDownloadResume = null;

    // Company / Recruiter
    private ?int $companyProfileView = null;
    private ?int $companyProfileEdit = null;
    private ?int $recruiterManage = null;

    // Admin
    private ?int $categoryManage = null;
    private ?int $userManage = null;
    private ?int $roleManage = null;

    private ?string $createdDate = null;
    private ?string $modifiedDate = null;

    private ?int $status = null;
    private ?int $isDeleted = null;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->userId)) {
            $collection['user_id'] = $this->userId;
        }

        // Job Management
        if (isset($this->jobView)) {
            $collection['job_view'] = $this->jobView;
        }
        if (isset($this->jobCreate)) {
            $collection['job_create'] = $this->jobCreate;
        }
        if (isset($this->jobEdit)) {
            $collection['job_edit'] = $this->jobEdit;
        }
        if (isset($this->jobDelete)) {
            $collection['job_delete'] = $this->jobDelete;
        }
        if (isset($this->jobPublish)) {
            $collection['job_publish'] = $this->jobPublish;
        }
        if (isset($this->jobClose)) {
            $collection['job_close'] = $this->jobClose;
        }

        // Application Management
        if (isset($this->applicationView)) {
            $collection['application_view'] = $this->applicationView;
        }
        if (isset($this->applicationShortlist)) {
            $collection['application_shortlist'] = $this->applicationShortlist;
        }
        if (isset($this->applicationReject)) {
            $collection['application_reject'] = $this->applicationReject;
        }
        if (isset($this->applicationDownloadResume)) {
            $collection['application_download_resume'] = $this->applicationDownloadResume;
        }

        // Company / Recruiter
        if (isset($this->companyProfileView)) {
            $collection['company_profile_view'] = $this->companyProfileView;
        }
        if (isset($this->companyProfileEdit)) {
            $collection['company_profile_edit'] = $this->companyProfileEdit;
        }
        if (isset($this->recruiterManage)) {
            $collection['recruiter_manage'] = $this->recruiterManage;
        }

        // Admin
        if (isset($this->categoryManage)) {
            $collection['category_manage'] = $this->categoryManage;
        }
        if (isset($this->userManage)) {
            $collection['user_manage'] = $this->userManage;
        }
        if (isset($this->roleManage)) {
            $collection['role_manage'] = $this->roleManage;
        }

        if (isset($this->createdDate)) {
            $collection['created_date'] = $this->createdDate;
        }
        if (isset($this->modifiedDate)) {
            $collection['modified_date'] = $this->modifiedDate;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }
        if (isset($this->isDeleted)) {
            $collection['is_deleted'] = $this->isDeleted;
        }

        return $collection;
    }

    public function getUserId()
    {
        return $this->userId;
    }
    public function setUserId(int $val): self
    {
        $this->userId = $val;
        return $this;
    }

    public function setJobView(int $val): self
    {
        $this->jobView = $val;
        return $this;
    }
    public function setJobCreate(int $val): self
    {
        $this->jobCreate = $val;
        return $this;
    }
    public function setJobEdit(int $val): self
    {
        $this->jobEdit = $val;
        return $this;
    }
    public function setJobDelete(int $val): self
    {
        $this->jobDelete = $val;
        return $this;
    }
    public function setJobPublish(int $val): self
    {
        $this->jobPublish = $val;
        return $this;
    }
    public function setJobClose(int $val): self
    {
        $this->jobClose = $val;
        return $this;
    }

    public function setApplicationView(int $val): self
    {
        $this->applicationView = $val;
        return $this;
    }
    public function setApplicationShortlist(int $val): self
    {
        $this->applicationShortlist = $val;
        return $this;
    }
    public function setApplicationReject(int $val): self
    {
        $this->applicationReject = $val;
        return $this;
    }
    public function setApplicationDownloadResume(int $val): self
    {
        $this->applicationDownloadResume = $val;
        return $this;
    }

    public function setCompanyProfileView(int $val): self
    {
        $this->companyProfileView = $val;
        return $this;
    }
    public function setCompanyProfileEdit(int $val): self
    {
        $this->companyProfileEdit = $val;
        return $this;
    }
    public function setRecruiterManage(int $val): self
    {
        $this->recruiterManage = $val;
        return $this;
    }

    public function setCategoryManage(int $val): self
    {
        $this->categoryManage = $val;
        return $this;
    }
    public function setUserManage(int $val): self
    {
        $this->userManage = $val;
        return $this;
    }
    public function setRoleManage(int $val): self
    {
        $this->roleManage = $val;
        return $this;
    }

    public function setCreatedDate(string $val): self
    {
        $this->createdDate = $val;
        return $this;
    }
    public function setModifiedDate(string $val): self
    {
        $this->modifiedDate = $val;
        return $this;
    }
    public function getStatus(): ?int
    {
        return $this->status;
    }
    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }
    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }
    public function setIsDeleted(?int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
