<?php

namespace App\Modules\Auth\Signup\Services;

use App\Constants\CommonConstant;
use App\Constants\UserConstant;
use App\Exceptions\UserNotFoundException;
use App\Modules\Auth\Login\RoleBased\UserLoginService;
use App\Modules\V1\User\Bo\Add\UserDetailsBo;
use App\Repositories\DAO\V1\AllUserAccessRightsDAO;
use App\Repositories\V1\AllUserAccessRightRepository;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Support\Collection;
use Throwable;

class LoginService
{
    private ?int $userId = null;

    public function __construct(
        private UserDetailsBo $userDetailsBo,
        private UserRepository $userRepository
    ) {}

    public function add(string $email, string $password, string $browserIp, string $userAgent): array
    {
        try {
            $user = $this->validateUser($email, $password);
            $token = $this->createToken($user, $browserIp, $userAgent);
            $this->insertAcessRights();

            return ['status' => CommonConstant::SUCCESS, 'data' => ['name' => $user->get('first_name') . ' ' . $user->get('last_name'), ...$token]];
        } catch (UserNotFoundException $e) {
            return CommonUtils::errorResponse($e->getMessage());
        } catch (Throwable $e) {
            throw $e;
        }
    }

    private function validateUser($email, $password): Collection
    {
        $user = collect($this->userRepository->findByEmailAndPassword($email, $password)->first());

        if ($user->isNotEmpty()) {
            if ($user->get('verified') == UserConstant::IS_UNVERIFIED) {
                throw UserNotFoundException::withMessage('Please verify your account to login');
            } else {
                $this->userId = $user->get('id');
                return $user;
            }
        }
        throw UserNotFoundException::withMessage();
    }

    private function createToken(Collection $userData, ?string $browserIp, ?string $userAgent): array
    {
        $userLoginService = app(UserLoginService::class);
        return $userLoginService->createToken($userData, $browserIp, $userAgent);
    }

    private function insertAcessRights(): void
    {
        $allUserAccessRightRepository = app(AllUserAccessRightRepository::class);
        $allUserAccessRights = $allUserAccessRightRepository->findByUserId($this->userId);

        if ($allUserAccessRights->isEmpty()) {
            $allUserAccessRightsDAO = $this->createUserAccessRightsDAO();
            $allUserAccessRightRepository->insert($allUserAccessRightsDAO);
        }
    }

    private function createUserAccessRightsDAO(): AllUserAccessRightsDAO
    {
        $allUserAccessRightsDAO = new AllUserAccessRightsDAO();

        $allUserAccessRightsDAO->setUserId($this->userId);
        $allUserAccessRightsDAO->setStatus(CommonConstant::STATUS_ACTIVE);
        $allUserAccessRightsDAO->setIsDeleted(CommonConstant::IS_DELETED_NO);

        return $allUserAccessRightsDAO;
    }
}