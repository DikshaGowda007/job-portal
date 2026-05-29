<?php

namespace App\Modules\V1\JobApplication\Services\Conversations;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Repositories\V1\ApplicationMessageRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private ApplicationMessageRepository $applicationMessageRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function get(): JsonResponse
    {
        try {
            $messages = $this->fetchMessages();
            $response = $this->formatResponse($messages);

            return response()->json(CommonUtils::successDataResponse($response));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    private function fetchMessages(): Collection
    {
        return $this->applicationMessageRepository->fetchByUserIdWithJobPostsAndSenderId($this->loggedInUserId);
    }

    private function formatResponse(Collection $messages): array
    {
        return $messages
            ->groupBy('application_id')
            ->map(fn ($msgs) => $this->formatConversation($msgs))
            ->sortByDesc('last_message_at')
            ->values()
            ->toArray();
    }

    private function formatConversation(Collection $msgs): array
    {
        $first = $msgs->first();
        $last = $msgs->last();

        return [
            'application_id' => $first->application_id,
            'job_title' => $first->job_title,
            'company_name' => $first->company_name,
            'last_message' => $last->message,
            'last_message_at' => $last->created_at,
            'messages' => $this->formatMessages($msgs),
        ];
    }

    private function formatMessages(Collection $msgs): array
    {
        $result = [];

        foreach ($msgs as $mssg) {
            $result[] = [
                'id' => $mssg->id,
                'message' => $mssg->message,
                'sender_id' => $mssg->sender_id,
                'sender' => $mssg->sender ? trim($mssg->sender->first_name.' '.$mssg->sender->last_name) : null,
                'created_at' => $mssg->created_at,
            ];
        }

        return $result;
    }
}
