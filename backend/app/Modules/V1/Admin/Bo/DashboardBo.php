<?php

namespace App\Modules\V1\Admin\Bo;

class DashboardBo
{
    private ?string $startDate;

    private ?string $endDate;

    public function toArray(): array
    {
        $collection = [];

        if (isset($this->startDate)) {
            $collection['start_date'] = $this->startDate;
        }
        if (isset($this->endDate)) {
            $collection['end_date'] = $this->endDate;
        }

        return $collection;
    }

    /**
     * Get the value of startDate
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * Set the value of startDate
     */
    public function setStartDate(?string $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get the value of endDate
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate
     */
    public function setEndDate(?string $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }
}
