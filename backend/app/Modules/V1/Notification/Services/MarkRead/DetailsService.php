<?php

namespace App\Modules\V1\Notification\Services\MarkRead;

use App\Constants\CommonConstant;
use App\Repositories\V1\NotificationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function markRead(?int $notificationId): JsonResponse
    {
        try {
            if ($notificationId) {
                $this->notificationRepository->updateByIdAndUserIdAndNotificationId($this->loggedInUserId, $notificationId);
            } else {
                $this->notificationRepository->updateByUserIdAndNotificationId($this->loggedInUserId);
            }

            return $this->formatResponse();
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to mark as read'));
        }
    }

    private function formatResponse(): JsonResponse
    {
        return response()->json(CommonUtils::successResponse('Marked as read'));
    }
}
