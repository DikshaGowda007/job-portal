<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Modules\V1\Notification\Services\List\DetailsService as ListDetailsService;
use App\Modules\V1\Notification\Services\MarkRead\DetailsService as MarkReadDetailsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class NotificationController extends Controller
{
    public function list(): JsonResponse
    {
        try {
            return app(ListDetailsService::class)->list();
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function markRead(Request $request): JsonResponse
    {
        try {
            $notificationId = $request->input('notification_id') ? (int) $request->input('notification_id') : null;

            return app(MarkReadDetailsService::class)->markRead($notificationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
