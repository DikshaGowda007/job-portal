<?php

namespace App\Modules\V1\User\Services\Profile;

use App\Constants\CommonConstant;
use App\Repositories\V1\UserRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private UserRepository $userRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function getProfile(): JsonResponse
    {
        try {
            $user = collect($this->userRepository->findById($this->loggedInUserId)->first());

            if ($user->isEmpty()) {
                return response()->json(CommonUtils::errorResponse('User not found'));
            }

            $data = [
                'id' => $user->get('id'),
                'first_name' => $user->get('first_name'),
                'last_name' => $user->get('last_name'),
                'email' => $user->get('email'),
                'user_type' => $user->get('user_type'),
                'verified' => $user->get('verified'),
                'last_login' => $user->get('last_login'),
                'created_at' => $user->get('created_at'),
            ];

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to fetch profile'));
        }
    }
}
