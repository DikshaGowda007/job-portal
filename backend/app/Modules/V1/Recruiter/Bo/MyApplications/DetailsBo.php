<?php

namespace App\Modules\V1\Recruiter\Bo\MyApplications;

class DetailsBo
{
    private ?string $text = null;

    private ?int $jobPostId = null;

    private ?string $status = null;

    private ?string $dateFrom = null;

    private ?string $dateTo = null;

    private int $page = 1;

    private int $perPage = 20;

    private string $sortBy = 'job_applications.created_at';

    private string $sortOrder = 'desc';

    public function toArray(): array
    {
        $collection = [
            'page' => $this->page,
            'per_page' => $this->perPage,
            'sort_by' => $this->sortBy,
            'sort_order' => $this->sortOrder,
        ];

        if (isset($this->text)) {
            $collection['text'] = $this->text;
        }
        if (isset($this->jobPostId)) {
            $collection['job_post_id'] = $this->jobPostId;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }
        if (isset($this->dateFrom)) {
            $collection['date_from'] = $this->dateFrom;
        }
        if (isset($this->dateTo)) {
            $collection['date_to'] = $this->dateTo;
        }

        return $collection;
    }

    /**
     * Get the value of text
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Set the value of text
     */
    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of jobPostId
     */
    public function getJobPostId(): ?int
    {
        return $this->jobPostId;
    }

    /**
     * Set the value of jobPostId
     */
    public function setJobPostId(?int $jobPostId): self
    {
        $this->jobPostId = $jobPostId;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of dateFrom
     */
    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    /**
     * Set the value of dateFrom
     */
    public function setDateFrom(?string $dateFrom): self
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * Get the value of dateTo
     */
    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }

    /**
     * Set the value of dateTo
     */
    public function setDateTo(?string $dateTo): self
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * Get the value of page
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Set the value of page
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of perPage
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Set the value of perPage
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Get the value of sortBy
     */
    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    /**
     * Set the value of sortBy
     */
    public function setSortBy(string $sortBy): self
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    /**
     * Get the value of sortOrder
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * Set the value of sortOrder
     */
    public function setSortOrder(string $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }
}
