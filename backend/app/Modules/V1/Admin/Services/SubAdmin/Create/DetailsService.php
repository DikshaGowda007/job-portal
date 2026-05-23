<?php

namespace App\Modules\V1\Admin\Services\SubAdmin\Create;

use App\Constants\CommonConstant;
use App\Http\Requests\V1\Admin\CreateSubAdminRequest;
use App\Models\User;
use App\Modules\V1\Admin\Bo\CreateSubAdminBo;
use App\Modules\V1\Admin\Helpers\AdminHelper;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    private CreateSubAdminBo $createSubAdminBo;

    public function __construct(
        private AdminHelper $adminHelper,
        private UserRepository $userRepository,
    ) {}

    public function createSubAdmin(CreateSubAdminBo $bo): JsonResponse
    {
        $this->createSubAdminBo = $bo;
        try {
            $userDetails = $this->insertUser();

            $response = $this->formatResponse($userDetails);

            return response()->json(CommonUtils::successDataResponse($response));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_ERROR);

            return response()->json(CommonUtils::errorResponse('Failed to create sub-admin'));
        }
    }

    public function prepareBo(CreateSubAdminRequest $createSubAdminRequest): CreateSubAdminBo
    {
        return $this->adminHelper->prepareCreateSubAdminBo($createSubAdminRequest);
    }

    private function formatResponse($userDetails): array
    {
        return [
            'user_id' => $userDetails->id,
            'email' => $userDetails->email,
            'role' => $userDetails->role,
        ];

    }

    private function insertUser(): User
    {
        $userDao = $this->adminHelper->prepareCreateSubAdminDao($this->createSubAdminBo);

        return $this->userRepository->insert($userDao);
    }
}
