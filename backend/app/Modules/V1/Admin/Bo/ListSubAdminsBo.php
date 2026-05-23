<?php

namespace App\Modules\V1\Admin\Bo;

class ListSubAdminsBo
{
    private ?string $status;

    private int $page = 1;

    private int $perPage = 20;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }
        if (isset($this->page)) {
            $collection['page'] = $this->page;
        }
        if (isset($this->perPage)) {
            $collection['per_page'] = $this->perPage;
        }

        return $collection;
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
}
