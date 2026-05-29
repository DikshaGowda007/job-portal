<?php

namespace App\Repositories\DAO\V1;

class NotificationDAO
{
    private ?int $userId = null;

    private ?string $type = null;

    private ?string $title = null;

    private ?string $body = null;

    private ?int $linkId = null;

    private int $isRead = 0;

    private ?string $createdAt = null;

    public function toArray(): array
    {
        $data = [
            'user_id' => $this->userId,
            'type' => $this->type,
            'title' => $this->title,
            'body' => $this->body,
            'is_read' => $this->isRead,
        ];

        if ($this->linkId !== null) {
            $data['link_id'] = $this->linkId;
        }

        if ($this->createdAt !== null) {
            $data['created_at'] = $this->createdAt;
        }

        return $data;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getLinkId(): ?int
    {
        return $this->linkId;
    }

    public function setLinkId(?int $linkId): self
    {
        $this->linkId = $linkId;

        return $this;
    }

    public function getIsRead(): int
    {
        return $this->isRead;
    }

    public function setIsRead(int $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
