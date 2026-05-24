<?php

namespace App\Modules\V1\JobCategory\Bo\Add;

class DetailsBo
{
    private ?string $name = null;

    private ?string $slug = null;

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

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->name)) {
            $collection['name'] = $this->name;
        }
        if (isset($this->slug)) {
            $collection['slug'] = $this->slug;
        }

        return $collection;
    }
}
