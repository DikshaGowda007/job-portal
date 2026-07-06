<?php

namespace App\Http\Controllers\JobApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\JobApplication\Apply\DetailsRequest as ApplyDetailsRequest;
use App\Http\Requests\V1\JobApplication\Get\DetailsRequest as GetDetailsRequest;
use App\Http\Requests\V1\JobApplication\History\DetailsRequest as HistoryDetailsRequest;
use App\Http\Requests\V1\JobApplication\List\DetailsRequest as ListDetailsRequest;
use App\Http\Requests\V1\JobApplication\MarkRead\DetailsRequest as MarkReadDetailsRequest;
use App\Http\Requests\V1\JobApplication\MyApplications\DetailsRequest as MyApplicationsDetailsRequest;
use App\Http\Requests\V1\JobApplication\SendMessage\DetailsRequest as SendMessageDetailsRequest;
use App\Http\Requests\V1\JobApplication\Typing\DetailsRequest as TypingDetailsRequest;
use App\Http\Requests\V1\JobApplication\UpdateStatus\DetailsRequest as UpdateStatusDetailsRequest;
use App\Http\Requests\V1\JobApplication\View\DetailsRequest as ViewDetailsRequest;
use App\Http\Requests\V1\JobApplication\Withdraw\DetailsRequest as WithdrawDetailsRequest;
use App\Modules\V1\JobApplication\Services\Apply\DetailsService as ApplyDetailsService;
use App\Modules\V1\JobApplication\Services\Conversations\DetailsService as ConversationsDetailsService;
use App\Modules\V1\JobApplication\Services\Get\DetailsService as GetDetailsService;
use App\Modules\V1\JobApplication\Services\History\DetailsService as HistoryDetailsService;
use App\Modules\V1\JobApplication\Services\List\DetailsService as ListDetailsService;
use App\Modules\V1\JobApplication\Services\MarkRead\DetailsService as MarkReadDetailsService;
use App\Modules\V1\JobApplication\Services\MyApplications\DetailsService as MyApplicationsDetailsService;
use App\Modules\V1\JobApplication\Services\RecruiterConversations\DetailsService as RecruiterConversationsDetailsService;
use App\Modules\V1\JobApplication\Services\RecruiterSendMessage\DetailsService as RecruiterSendMessageDetailsService;
use App\Modules\V1\JobApplication\Services\SendMessage\DetailsService as SendMessageDetailsService;
use App\Modules\V1\JobApplication\Services\Typing\DetailsService as TypingDetailsService;
use App\Modules\V1\JobApplication\Services\UpdateStatus\DetailsService as UpdateStatusDetailsService;
use App\Modules\V1\JobApplication\Services\View\DetailsService as ViewDetailsService;
use App\Modules\V1\JobApplication\Services\Withdraw\DetailsService as WithdrawDetailsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class JobApplicationController extends Controller
{
    public function list(ListDetailsRequest $listDetailsRequest): JsonResponse
    {
        try {
            $listDetailsService = app(ListDetailsService::class);

            return $listDetailsService->list(
                (int) $listDetailsRequest->input('page', 1),
                (int) $listDetailsRequest->input('per_page', 20),
                $listDetailsRequest->input('status'),
                $listDetailsRequest->input('text'),
                $listDetailsRequest->input('job_post_id') !== null
                ? (int) $listDetailsRequest->input('job_post_id')
                : null,
            );
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function apply(ApplyDetailsRequest $request): JsonResponse
    {
        try {
            $applyDetailsService = app(ApplyDetailsService::class);
            $applyDetailsBo = $applyDetailsService->prepareBo($request);

            return $applyDetailsService->apply($applyDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function myApplications(MyApplicationsDetailsRequest $myApplicationsDetailsRequest): JsonResponse
    {
        try {
            $myApplicationsDetailsService = app(MyApplicationsDetailsService::class);
            $page = $myApplicationsDetailsRequest->input('page', 1);
            $perPage = $myApplicationsDetailsRequest->input('per_page', 20);

            return $myApplicationsDetailsService->myApplications($page, $perPage);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function get(GetDetailsRequest $getDetailsRequest): JsonResponse
    {
        try {
            $getDetailsService = app(GetDetailsService::class);
            $applicationId = $getDetailsRequest->input('application_id');

            return $getDetailsService->get($applicationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function withdraw(WithdrawDetailsRequest $request): JsonResponse
    {
        try {
            $withdrawDetailsService = app(WithdrawDetailsService::class);
            $applicationId = $request->input('application_id');

            return $withdrawDetailsService->withdraw($applicationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function view(ViewDetailsRequest $viewDetailsRequest): JsonResponse
    {
        try {
            $viewDetailsService = app(ViewDetailsService::class);
            $applicationId = $viewDetailsRequest->input('application_id');

            return $viewDetailsService->view($applicationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function history(HistoryDetailsRequest $historyDetailsRequest): JsonResponse
    {
        try {
            $historyDetailsService = app(HistoryDetailsService::class);
            $applicationId = $historyDetailsRequest->input('application_id');

            return $historyDetailsService->getHistory($applicationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function updateStatus(UpdateStatusDetailsRequest $updateStatusDetailsRequest): JsonResponse
    {
        try {
            $updateStatusDetailsService = app(UpdateStatusDetailsService::class);
            $updateStatusDetailsBo = $updateStatusDetailsService->prepareBo($updateStatusDetailsRequest);

            return $updateStatusDetailsService->updateStatus($updateStatusDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function conversations(): JsonResponse
    {
        try {
            $conversationsDetailsService = app(ConversationsDetailsService::class);

            return $conversationsDetailsService->get();
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function sendMessage(SendMessageDetailsRequest $sendMessageDetailsRequest): JsonResponse
    {
        try {
            $applicationId = (int) $sendMessageDetailsRequest->input('application_id');
            $message = $sendMessageDetailsRequest->input('message');
            $sendMessageDetailsService = app(SendMessageDetailsService::class);

            return $sendMessageDetailsService->send($applicationId, $message);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function recruiterConversations(): JsonResponse
    {
        try {
            $service = app(RecruiterConversationsDetailsService::class);

            return $service->get();
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function recruiterSendMessage(SendMessageDetailsRequest $sendMessageDetailsRequest): JsonResponse
    {
        try {
            $applicationId = (int) $sendMessageDetailsRequest->input('application_id');
            $message = $sendMessageDetailsRequest->input('message');
            $service = app(RecruiterSendMessageDetailsService::class);

            return $service->send($applicationId, $message);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function markRead(MarkReadDetailsRequest $markReadDetailsRequest): JsonResponse
    {
        try {
            $applicationId = (int) $markReadDetailsRequest->input('application_id');
            $markReadDetailsService = app(MarkReadDetailsService::class);

            return $markReadDetailsService->markRead($applicationId);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function typing(TypingDetailsRequest $typingDetailsRequest): JsonResponse
    {
        try {
            $applicationId = (int) $typingDetailsRequest->input('application_id');
            $isTyping = (bool) $typingDetailsRequest->input('is_typing');
            $typingDetailsService = app(TypingDetailsService::class);

            return $typingDetailsService->typing($applicationId, $isTyping);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
