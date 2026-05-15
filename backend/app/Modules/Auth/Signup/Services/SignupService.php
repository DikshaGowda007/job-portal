<?php

namespace App\Modules\Auth\Signup\Services;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\User\Add\UserRequest;
use App\Modules\V1\User\Bo\Add\UserDetailsBo;
use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\V1\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;

class SignupService
{
    public function __construct(
        private UserDetailsBo $userDetailsBo,
        private UserDAO $userDAO,
        private UserRepository $userRepository
    ) {}

    public function add(UserDetailsBo $userDetailsBo): JsonResponse
    {
        $this->userDetailsBo = $userDetailsBo;
        try {
            $data = $this->insert();
            $this->verifyOTP($data);

            return response()->json(['status' => CommonConstant::SUCCESS, 'message' => CommonConstant::OTP_SENT_SUCCESS, 'data' => $data]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => ErrorResponseConstant::ERROR_MESSAGE_GENERAL]);

        }
    }

    public function prepareBo(UserRequest $userRequest): UserDetailsBo
    {
        $this->userDetailsBo->setFirstName($userRequest->input('first_name'));
        $this->userDetailsBo->setLastName($userRequest->input('last_name'));
        $this->userDetailsBo->setEmail($userRequest->input('email'));
        $this->userDetailsBo->setPassword($userRequest->input('password'));
        $this->userDetailsBo->setUserType((int) $userRequest->input('user_type'));

        return $this->userDetailsBo;
    }

    public function prepareDAO(): UserDAO
    {
        $this->userDAO->setFirstName($this->userDetailsBo->getFirstName());
        $this->userDAO->setLastName($this->userDetailsBo->getLastName());
        $this->userDAO->setEmail($this->userDetailsBo->getEmail());
        $this->userDAO->setPassword($this->userDetailsBo->getPassword());
        $this->userDAO->setUserType($this->userDetailsBo->getUserType());

        return $this->userDAO;
    }

    private function insert(): array
    {
        $this->prepareDAO();

        $userData = $this->userRepository->insert($this->userDAO);

        if (! $userData) {
            throw new Exception('Insert failed');
        }

        return $userData->toArray();
    }

    private function verifyOTP(array $user): array
    {
        return app(OtpService::class)->sendOtp($user['id'], $user['email']);
    }
}
