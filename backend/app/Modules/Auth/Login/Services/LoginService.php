<?php

namespace App\Modules\Auth\Login\Services;

use App\Constants\CommonConstant;
use App\Constants\UserConstant;
use App\Exceptions\UserNotFoundException;
use App\Modules\Auth\Login\RoleBased\UserLoginService;
use App\Repositories\DAO\V1\AllUserAccessRightsDAO;
use App\Repositories\V1\AllUserAccessRightRepository;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Throwable;

class LoginService
{
    private ?int $userId = null;

    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function add(string $email, string $password): array
    {
        try {
            $user = $this->validateUser($email, $password);
            $token = $this->createToken($user);
            $this->insertAccessRights();
            $this->queueCookie($token['request_token']);

            return [
                'status' => CommonConstant::SUCCESS,
                'data' => ['name' => $user->get('first_name').' '.$user->get('last_name'), ...$token],
            ];
        } catch (UserNotFoundException $e) {
            return CommonUtils::errorResponse($e->getMessage());
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function validateUser(string $email, string $password): Collection
    {
        $userDetails = collect($this->userRepository->findByEmail($email)->first());

        if ($userDetails->isEmpty() || ! Hash::check($password, $userDetails->get('password'))) {
            throw UserNotFoundException::withMessage();
        }

        if ($userDetails->get('verified') == UserConstant::IS_UNVERIFIED) {
            throw UserNotFoundException::withMessage('Please verify your account to login');
        }

        $this->userId = $userDetails->get('id');

        return $userDetails;
    }

    private function createToken(Collection $userData): array
    {
        $userLoginService = app(UserLoginService::class);

        return $userLoginService->createToken($userData);
    }

    private function insertAccessRights(): void
    {
        $allUserAccessRightRepository = app(AllUserAccessRightRepository::class);
        $allUserAccessRights = $allUserAccessRightRepository->findByUserId($this->userId);

        if ($allUserAccessRights->isEmpty()) {
            $allUserAccessRightRepository->insert($this->createUserAccessRightsDao());
        }
    }

    private function createUserAccessRightsDao(): AllUserAccessRightsDAO
    {
        $allUserAccessRightsDao = new AllUserAccessRightsDAO;
        $allUserAccessRightsDao->setUserId($this->userId);
        $allUserAccessRightsDao->setStatus(CommonConstant::STATUS_ACTIVE);
        $allUserAccessRightsDao->setIsDeleted(CommonConstant::IS_DELETED_NO);

        return $allUserAccessRightsDao;
    }

    private function queueCookie(string $token): void
    {
        Cookie::queue('token', $token, 60 * 24, '/', null, true, true, false, 'Strict');
    }
}
