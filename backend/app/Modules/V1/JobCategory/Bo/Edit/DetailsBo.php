<?php

namespace App\Modules\V1\JobCategory\Bo\Edit;

class DetailsBo
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $slug = null;

    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->id)) {
            $collection['id'] = $this->id;
        }
        if (isset($this->name)) {
            $collection['name'] = $this->name;
        }
        if (isset($this->slug)) {
            $collection['slug'] = $this->slug;
        }
        if (isset($this->status)) {
            $collection['status'] = $this->status;
        }

        return $collection;
    }
}
