<?php

namespace App\Modules\V1\JobApplication\Services\Conversations;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Repositories\V1\ApplicationMessageRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

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
        return $this->applicationMessageRepository
            ->fetchByUserIdWithJobPostsAndSenderId($this->loggedInUserId)
            ->map(fn ($msg) => collect([
                'id' => $msg->id,
                'application_id' => $msg->application_id,
                'sender_id' => $msg->sender_id,
                'message' => $msg->message,
                'read_at' => $msg->read_at,
                'created_at' => $msg->created_at,
                'job_title' => $msg->job_title,
                'company_name' => $msg->company_name,
                'sender' => $msg->sender,
            ]));
    }

    private function formatResponse(Collection $messages): array
    {
        return $messages
            ->groupBy('application_id')
            ->map(fn ($msgs) => $this->formatConversation(collect($msgs)))
            ->sortByDesc('last_message_at')
            ->values()
            ->toArray();
    }

    private function formatConversation(Collection $msgs): array
    {
        $first = $msgs->first();
        $last = $msgs->last();

        return [
            'application_id' => $first->get('application_id'),
            'job_title' => $first->get('job_title'),
            'company_name' => $first->get('company_name'),
            'last_message' => $last->get('message'),
            'last_message_at' => $last->get('created_at'),
            'unread_count' => $msgs->filter(fn ($m) => $m->get('sender_id') !== $this->loggedInUserId && is_null($m->get('read_at')))->count(),
            'messages' => $this->formatMessages($msgs),
        ];
    }

    private function formatMessages(Collection $msgs): array
    {
        $result = [];

        foreach ($msgs as $mssg) {
            $result[] = [
                'id' => $mssg->get('id'),
                'message' => $mssg->get('message'),
                'sender_id' => $mssg->get('sender_id'),
                'sender' => $mssg->get('sender') ? trim($mssg->get('sender')->first_name.' '.$mssg->get('sender')->last_name) : null,
                'read_at' => $mssg->get('read_at'),
                'created_at' => $mssg->get('created_at'),
            ];
        }

        return $result;
    }
}
