<?php

namespace App\Modules\Auth\Services;

use App\Constants\CommonConstant;
use App\Http\Services\AuthService;
use App\Modules\Auth\JwtService;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class RefreshTokenService
{
    public function __construct(
        private AuthService $authService,
        private UserRepository $userRepository,
        private JwtService $jwtService,
    ) {}

    public function refresh(): JsonResponse
    {
        try {
            $userData = $this->authService->getData();
            $userId = $userData->get('userId');

            $user = collect($this->userRepository->findById($userId)->first());

            if ($user->isEmpty()) {
                return response()->json(CommonUtils::errorResponse('User not found'), 401);
            }

            if ($user->get('status') == CommonConstant::STATUS_INACTIVE) {
                return response()->json(CommonUtils::errorResponse('Account is inactive'), 401);
            }

            $payload = [
                'is_loggedin' => 1,
                'loggedin_user_type' => CommonUtils::xssClean($user->get('user_type')),
                'loggedin_user_id' => $user->get('id'),
                'loggedin_user_first_name' => CommonUtils::xssClean($user->get('first_name')),
                'loggedin_user_last_name' => CommonUtils::xssClean($user->get('last_name')),
                'loggedin_user_email' => CommonUtils::xssClean($user->get('email')),
                'loggedin_user_mobile' => CommonUtils::xssClean($user->get('mobile') ?? ''),
            ];

            $token = $this->jwtService->generateToken($payload);

            return response()->json(CommonUtils::successDataResponse([
                'request_token' => $token,
            ]));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to refresh token'), 401);
        }
    }
}
