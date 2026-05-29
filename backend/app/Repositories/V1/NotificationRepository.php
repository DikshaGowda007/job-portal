<?php

namespace App\Repositories\V1;

use App\Models\Notification;
use App\Repositories\DAO\V1\NotificationDAO;
use Illuminate\Database\Eloquent\Collection;

interface NotificationRepository
{
    public function insert(NotificationDAO $notificationDAO): Notification;

    public function fetchByUserId(int $userId, int $limit): Collection;

    public function findByIsRead(int $userId): int;

    public function updateByIdAndUserIdAndNotificationId(int $userId, int $notificationId): void;

    public function updateByUserIdAndNotificationId(int $userId): void;
}
