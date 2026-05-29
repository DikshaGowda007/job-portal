<?php

namespace App\Repositories\DAO\V1;

class AllUserAccessRightsDAO
{
    private ?int $userId = null;

    private ?int $jobView = null;

    private ?int $jobEdit = null;

    private ?int $jobDelete = null;

    private ?int $jobPublish = null;

    private ?int $jobClose = null;

    private ?int $jobApply = null;

    private ?int $applicationView = null;

    private ?int $applicationStatusUpdate = null;

    private ?int $applicationShortlist = null;

    private ?int $applicationReject = null;

    private ?int $applicationWithdraw = null;

    private ?int $applicationDownloadResume = null;

    private ?int $companyProfileView = null;

    private ?int $companyProfileEdit = null;

    // Admin
    private ?int $categoryView = null;

    private ?int $categoryAdd = null;

    private ?int $categoryEdit = null;

    private ?int $categoryDelete = null;

    private ?int $userEdit = null;

    private ?int $userAdd = null;

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
        if (isset($this->jobView)) {
            $collection['job_view'] = $this->jobView;
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
        if (isset($this->jobApply)) {
            $collection['job_apply'] = $this->jobApply;
        }
        if (isset($this->applicationView)) {
            $collection['application_view'] = $this->applicationView;
        }
        if (isset($this->applicationStatusUpdate)) {
            $collection['application_status_update'] = $this->applicationStatusUpdate;
        }
        if (isset($this->applicationShortlist)) {
            $collection['application_shortlist'] = $this->applicationShortlist;
        }
        if (isset($this->applicationReject)) {
            $collection['application_reject'] = $this->applicationReject;
        }
        if (isset($this->applicationWithdraw)) {
            $collection['application_withdraw'] = $this->applicationWithdraw;
        }
        if (isset($this->applicationDownloadResume)) {
            $collection['application_download_resume'] = $this->applicationDownloadResume;
        }
        if (isset($this->companyProfileView)) {
            $collection['company_profile_view'] = $this->companyProfileView;
        }
        if (isset($this->companyProfileEdit)) {
            $collection['company_profile_edit'] = $this->companyProfileEdit;
        }
        if (isset($this->categoryView)) {
            $collection['category_view'] = $this->categoryView;
        }
        if (isset($this->categoryAdd)) {
            $collection['category_add'] = $this->categoryAdd;
        }
        if (isset($this->categoryEdit)) {
            $collection['category_edit'] = $this->categoryEdit;
        }
        if (isset($this->categoryDelete)) {
            $collection['category_delete'] = $this->categoryDelete;
        }
        if (isset($this->userEdit)) {
            $collection['user_edit'] = $this->userEdit;
        }
        if (isset($this->userAdd)) {
            $collection['user_add'] = $this->userAdd;
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

    /**
     * Get the value of userId
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of jobView
     */
    public function getJobView(): ?int
    {
        return $this->jobView;
    }

    /**
     * Set the value of jobView
     */
    public function setJobView(?int $jobView): self
    {
        $this->jobView = $jobView;

        return $this;
    }

    /**
     * Get the value of jobEdit
     */
    public function getJobEdit(): ?int
    {
        return $this->jobEdit;
    }

    /**
     * Set the value of jobEdit
     */
    public function setJobEdit(?int $jobEdit): self
    {
        $this->jobEdit = $jobEdit;

        return $this;
    }

    /**
     * Get the value of jobDelete
     */
    public function getJobDelete(): ?int
    {
        return $this->jobDelete;
    }

    /**
     * Set the value of jobDelete
     */
    public function setJobDelete(?int $jobDelete): self
    {
        $this->jobDelete = $jobDelete;

        return $this;
    }

    /**
     * Get the value of jobPublish
     */
    public function getJobPublish(): ?int
    {
        return $this->jobPublish;
    }

    /**
     * Set the value of jobPublish
     */
    public function setJobPublish(?int $jobPublish): self
    {
        $this->jobPublish = $jobPublish;

        return $this;
    }

    /**
     * Get the value of jobClose
     */
    public function getJobClose(): ?int
    {
        return $this->jobClose;
    }

    /**
     * Set the value of jobClose
     */
    public function setJobClose(?int $jobClose): self
    {
        $this->jobClose = $jobClose;

        return $this;
    }

    /**
     * Get the value of jobApply
     */
    public function getJobApply(): ?int
    {
        return $this->jobApply;
    }

    /**
     * Set the value of jobApply
     */
    public function setJobApply(?int $jobApply): self
    {
        $this->jobApply = $jobApply;

        return $this;
    }

    /**
     * Get the value of applicationView
     */
    public function getApplicationView(): ?int
    {
        return $this->applicationView;
    }

    /**
     * Set the value of applicationView
     */
    public function setApplicationView(?int $applicationView): self
    {
        $this->applicationView = $applicationView;

        return $this;
    }

    /**
     * Get the value of applicationStatusUpdate
     */
    public function getApplicationStatusUpdate(): ?int
    {
        return $this->applicationStatusUpdate;
    }

    /**
     * Set the value of applicationStatusUpdate
     */
    public function setApplicationStatusUpdate(?int $applicationStatusUpdate): self
    {
        $this->applicationStatusUpdate = $applicationStatusUpdate;

        return $this;
    }

    /**
     * Get the value of applicationShortlist
     */
    public function getApplicationShortlist(): ?int
    {
        return $this->applicationShortlist;
    }

    /**
     * Set the value of applicationShortlist
     */
    public function setApplicationShortlist(?int $applicationShortlist): self
    {
        $this->applicationShortlist = $applicationShortlist;

        return $this;
    }

    /**
     * Get the value of applicationReject
     */
    public function getApplicationReject(): ?int
    {
        return $this->applicationReject;
    }

    /**
     * Set the value of applicationReject
     */
    public function setApplicationReject(?int $applicationReject): self
    {
        $this->applicationReject = $applicationReject;

        return $this;
    }

    /**
     * Get the value of applicationWithdraw
     */
    public function getApplicationWithdraw(): ?int
    {
        return $this->applicationWithdraw;
    }

    /**
     * Set the value of applicationWithdraw
     */
    public function setApplicationWithdraw(?int $applicationWithdraw): self
    {
        $this->applicationWithdraw = $applicationWithdraw;

        return $this;
    }

    /**
     * Get the value of applicationDownloadResume
     */
    public function getApplicationDownloadResume(): ?int
    {
        return $this->applicationDownloadResume;
    }

    /**
     * Set the value of applicationDownloadResume
     */
    public function setApplicationDownloadResume(?int $applicationDownloadResume): self
    {
        $this->applicationDownloadResume = $applicationDownloadResume;

        return $this;
    }

    /**
     * Get the value of companyProfileView
     */
    public function getCompanyProfileView(): ?int
    {
        return $this->companyProfileView;
    }

    /**
     * Set the value of companyProfileView
     */
    public function setCompanyProfileView(?int $companyProfileView): self
    {
        $this->companyProfileView = $companyProfileView;

        return $this;
    }

    /**
     * Get the value of companyProfileEdit
     */
    public function getCompanyProfileEdit(): ?int
    {
        return $this->companyProfileEdit;
    }

    /**
     * Set the value of companyProfileEdit
     */
    public function setCompanyProfileEdit(?int $companyProfileEdit): self
    {
        $this->companyProfileEdit = $companyProfileEdit;

        return $this;
    }

    /**
     * Get the value of categoryView
     */
    public function getCategoryView(): ?int
    {
        return $this->categoryView;
    }

    /**
     * Set the value of categoryView
     */
    public function setCategoryView(?int $categoryView): self
    {
        $this->categoryView = $categoryView;

        return $this;
    }

    /**
     * Get the value of categoryAdd
     */
    public function getCategoryAdd(): ?int
    {
        return $this->categoryAdd;
    }

    /**
     * Set the value of categoryAdd
     */
    public function setCategoryAdd(?int $categoryAdd): self
    {
        $this->categoryAdd = $categoryAdd;

        return $this;
    }

    /**
     * Get the value of categoryEdit
     */
    public function getCategoryEdit(): ?int
    {
        return $this->categoryEdit;
    }

    /**
     * Set the value of categoryEdit
     */
    public function setCategoryEdit(?int $categoryEdit): self
    {
        $this->categoryEdit = $categoryEdit;

        return $this;
    }

    /**
     * Get the value of categoryDelete
     */
    public function getCategoryDelete(): ?int
    {
        return $this->categoryDelete;
    }

    /**
     * Set the value of categoryDelete
     */
    public function setCategoryDelete(?int $categoryDelete): self
    {
        $this->categoryDelete = $categoryDelete;

        return $this;
    }

    /**
     * Get the value of userEdit
     */
    public function getUserEdit(): ?int
    {
        return $this->userEdit;
    }

    /**
     * Set the value of userEdit
     */
    public function setUserEdit(?int $userEdit): self
    {
        $this->userEdit = $userEdit;

        return $this;
    }

    /**
     * Get the value of userAdd
     */
    public function getUserAdd(): ?int
    {
        return $this->userAdd;
    }

    /**
     * Set the value of userAdd
     */
    public function setUserAdd(?int $userAdd): self
    {
        $this->userAdd = $userAdd;

        return $this;
    }

    /**
     * Get the value of roleManage
     */
    public function getRoleManage(): ?int
    {
        return $this->roleManage;
    }

    /**
     * Set the value of roleManage
     */
    public function setRoleManage(?int $roleManage): self
    {
        $this->roleManage = $roleManage;

        return $this;
    }

    /**
     * Get the value of createdDate
     */
    public function getCreatedDate(): ?string
    {
        return $this->createdDate;
    }

    /**
     * Set the value of createdDate
     */
    public function setCreatedDate(?string $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get the value of modifiedDate
     */
    public function getModifiedDate(): ?string
    {
        return $this->modifiedDate;
    }

    /**
     * Set the value of modifiedDate
     */
    public function setModifiedDate(?string $modifiedDate): self
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of isDeleted
     */
    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    /**
     * Set the value of isDeleted
     */
    public function setIsDeleted(?int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
