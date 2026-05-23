<?php

namespace App\Modules\V1\Admin\User\Bo\List;

class DetailsBo
{
    private ?string $role;

    private ?string $status;

    private ?string $search;

    private int $page = 1;

    private int $perPage = 20;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->role)) {
            $collection['role'] = $this->role;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }
        if (isset($this->search)) {
            $collection['search'] = $this->search;
        }
        if (isset($this->page)) {
            $collection['page'] = $this->page;
        }
        if (isset($this->perPage)) {
            $collection['per_page'] = $this->perPage;
        }

        return $collection;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }
}
