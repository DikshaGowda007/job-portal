<?php

namespace App\Modules\Auth\Login\RoleBased;

use App\Constants\CommonConstant;
use App\Exceptions\AccountInActiveException;
use App\Exceptions\UserNotFoundException;
use App\Modules\Auth\JwtService;
use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UserLoginService
{
    public function __construct(
        private UserRepository $userRepository,
        private JwtService $jwtService,
    ) {}

    public function createToken(Collection $user, string $browserIp, string $userAgent)
    {
        try {
            $this->validateLogin($user);
            $payload = $this->buildJwtPayload($user);

            $token = $this->jwtService->generateToken($payload);

            $data = [
                'request_token' => $token,
                'page' => 'index',
            ];
            $this->logUserLoginActivity($user->get('id'), $browserIp, $userAgent);

            return $data;

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function validateLogin(Collection $user)
    {
        if ($user->isEmpty()) {
            throw UserNotFoundException::withMessage();
        }

        return match ($user->get('status')) {
            CommonConstant::STATUS_ACTIVE => true,
            CommonConstant::STATUS_INACTIVE => throw AccountInActiveException::withMessage(),
            default => UserNotFoundException::withMessage(),
        };
    }

    private function buildJwtPayload(Collection $user)
    {
        return $this->basePayload($user);
    }

    private function basePayload(Collection $user): array
    {
        return [
            'is_loggedin' => 1,
            'loggedin_user_type' => CommonUtils::xssClean($user->get('user_type')),
            'loggedin_user_id' => $user->get('id'),
            'loggedin_user_first_name' => CommonUtils::xssClean($user->get('first_name')),
            'loggedin_user_last_name' => CommonUtils::xssClean($user->get('last_name')),
            'loggedin_user_email' => CommonUtils::xssClean($user->get('email')),
            'loggedin_user_mobile' => CommonUtils::xssClean($user->get('mobile')),
        ];
    }

    private function logUserLoginActivity(int $userId, string $browserIp, string $userAgent)
    {
        $this->updateUserLastLoginTime($userId);
    }

    private function updateUserLastLoginTime(int $userId): void
    {
        $userDao = new UserDAO();
        $userDao->setLastLogin(Carbon::now()->format('Y-m-d H:i:s'));

        $this->userRepository->updateById($userId, $userDao);
    }
}