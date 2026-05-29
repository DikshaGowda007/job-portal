<?php

namespace App\Modules\V1\Notification\Services\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\NotificationConstants;
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

    public function list(): JsonResponse
    {
        try {
            $notifications = $this->notificationRepository->fetchByUserId($this->loggedInUserId, NotificationConstants::LIST_LIMIT);
            $unreadCount = $this->notificationRepository->findByIsRead($this->loggedInUserId);

            return $this->formatResponse($notifications, $unreadCount);
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function formatResponse($notifications, int $unreadCount): JsonResponse
    {
        return response()->json(CommonUtils::successDataResponse([
            'notifications' => $notifications->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'body' => $n->body,
                'link_id' => $n->link_id,
                'is_read' => (bool) $n->is_read,
                'created_at' => $n->created_at,
            ])->values()->all(),
            'unread_count' => $unreadCount,
        ]));
    }
}
