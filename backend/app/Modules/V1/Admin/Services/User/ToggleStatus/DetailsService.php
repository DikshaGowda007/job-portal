<?php

namespace App\Modules\V1\Admin\Services\User\ToggleStatus;

use App\Constants\CommonConstant;
use App\Constants\UserConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\Admin\ToggleUserStatusRequest;
use App\Modules\V1\Admin\Bo\ToggleUserStatusBo;
use App\Modules\V1\Admin\Helpers\AdminHelper;
use App\Repositories\V1\UserRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    private ToggleUserStatusBo $toggleUserStatusBo;

    public function __construct(
        private AdminHelper $adminHelper,
        private UserRepository $userRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function toggleUserStatus(ToggleUserStatusBo $bo): JsonResponse
    {
        $this->toggleUserStatusBo = $bo;
        try {
            $user = $this->findUser();
            $this->validatePermission($user);
            $this->updateStatus();

            $message = $bo->getStatus() === 'active'
                ? 'User activated successfully'
                : 'User deactivated successfully';

            return response()->json(CommonUtils::successResponse($message));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_ERROR);

            return response()->json(CommonUtils::errorResponse('Failed to update user status'));
        }
    }

    public function prepareBo(ToggleUserStatusRequest $toggleUserStatusRequest): ToggleUserStatusBo
    {
        return $this->adminHelper->prepareToggleUserStatusBo($toggleUserStatusRequest);
    }

    private function findUser(): Collection
    {
        $userDetails = collect($this->userRepository->findById($this->toggleUserStatusBo->getUserId())->first());

        if ($userDetails->isEmpty()) {
            throw DataNotFoundException::withMessage('User not found');
        }

        return $userDetails;
    }

    private function validatePermission(Collection $user): void
    {
        if ($user->get('user_type') === UserConstant::USER_ROLE_ADMIN && $this->loggedInUserRole === UserConstant::USER_ROLE_SUB_ADMIN) {
            throw DataNotFoundException::withMessage('You do not have permission to modify admin users');
        }
    }

    private function updateStatus(): void
    {
        $userDao = $this->adminHelper->prepareToggleStatusDao($this->toggleUserStatusBo);
        $this->userRepository->updateById($this->toggleUserStatusBo->getUserId(), $userDao);
    }
}
