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

    public function createToken(Collection $userDetails): array
    {
        try {
            $this->validateLogin($userDetails);
            $payload = $this->prepareJwtPayload($userDetails);
            $token = $this->jwtService->generateToken($payload);
            $this->updateUserLastLoginTime($userDetails->get('id'));

            return [
                'request_token' => $token,
                'page' => 'index',
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function validateLogin(Collection $userDetails): void
    {
        if ($userDetails->isEmpty()) {
            throw UserNotFoundException::withMessage();
        }

        match ($userDetails->get('status')) {
            CommonConstant::STATUS_ACTIVE => true,
            CommonConstant::STATUS_INACTIVE => throw AccountInActiveException::withMessage(),
            default => throw UserNotFoundException::withMessage(),
        };
    }

    private function prepareJwtPayload(Collection $userDetails): array
    {
        return [
            'is_loggedin' => 1,
            'loggedin_user_type' => CommonUtils::xssClean($userDetails->get('user_type')),
            'loggedin_user_id' => $userDetails->get('id'),
            'loggedin_user_first_name' => CommonUtils::xssClean($userDetails->get('first_name')),
            'loggedin_user_last_name' => CommonUtils::xssClean($userDetails->get('last_name')),
            'loggedin_user_email' => CommonUtils::xssClean($userDetails->get('email')),
            'loggedin_user_mobile' => CommonUtils::xssClean($userDetails->get('mobile')),
        ];
    }

    private function updateUserLastLoginTime(int $userId): void
    {
        $userDao = new UserDAO;
        $userDao->setLastLogin(Carbon::now()->format('Y-m-d H:i:s'));

        $this->userRepository->updateById($userId, $userDao);
    }
}
