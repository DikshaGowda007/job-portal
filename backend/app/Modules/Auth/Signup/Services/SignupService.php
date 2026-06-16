<?php

namespace App\Modules\Auth\Signup\Services;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Modules\V1\User\Bo\Add\UserDetailsBo;
use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class SignupService
{
    public function __construct(
        private UserDetailsBo $userDetailsBo,
        private UserDAO $userDao,
        private UserRepository $userRepository
    ) {}

    public function add(UserDetailsBo $userDetailsBo): JsonResponse
    {
        $this->userDetailsBo = $userDetailsBo;
        try {
            $data = $this->insert();
            $this->verifyOtp($data);

            return response()->json(['status' => CommonConstant::SUCCESS, 'message' => CommonConstant::OTP_SENT_SUCCESS, 'data' => $data]);
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }

    public function prepareDao(): UserDAO
    {
        $this->userDao->setFirstName($this->userDetailsBo->getFirstName());
        $this->userDao->setLastName($this->userDetailsBo->getLastName());
        $this->userDao->setEmail($this->userDetailsBo->getEmail());
        $this->userDao->setPassword(Hash::make($this->userDetailsBo->getPassword()));
        $this->userDao->setUserType($this->userDetailsBo->getUserType());

        return $this->userDao;
    }

    private function insert(): array
    {
        $this->prepareDao();

        $userData = $this->userRepository->insert($this->userDao);

        if (! $userData) {
            throw new Exception('Insert failed');
        }

        return $userData->toArray();
    }

    private function verifyOtp(array $user): array
    {
        return app(OtpService::class)->sendOtp($user['id'], $user['email']);
    }
}
