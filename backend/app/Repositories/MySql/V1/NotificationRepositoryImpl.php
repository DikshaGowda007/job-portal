<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\Notification;
use App\Repositories\DAO\V1\NotificationDAO;
use App\Repositories\V1\NotificationRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepositoryImpl implements NotificationRepository
{
    public function insert(NotificationDAO $notificationDAO): Notification
    {
        $notificationDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return Notification::create($notificationDAO->toArray());
    }

    public function fetchByUserId(int $userId, int $limit): Collection
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function findByIsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', CommonConstant::STATUS_INACTIVE)
            ->count();
    }

    public function updateByIdAndUserIdAndNotificationId(int $userId, int $notificationId): void
    {
        Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->update(['is_read' => CommonConstant::STATUS_ACTIVE]);
    }

    public function updateByUserIdAndNotificationId(int $userId): void
    {
        Notification::where('user_id', $userId)
            ->where('is_read', CommonConstant::STATUS_INACTIVE)
            ->update(['is_read' => CommonConstant::STATUS_ACTIVE]);
    }
}
