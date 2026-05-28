<?php

namespace App\Modules\V1\User\Services\ChangePassword;

use App\Constants\CommonConstant;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Http\Requests\V1\User\ChangePassword\DetailsRequest;
use App\Modules\V1\User\Bo\ChangePassword\DetailsBo;
use App\Modules\V1\User\Helpers\UserHelper;
use App\Repositories\V1\UserRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private UserHelper $userHelper,
        private UserRepository $userRepository,
        private DetailsBo $detailsBo,
    ) {

        $this->initializeUserAuthorizationData();
    }

    public function changePassword(DetailsBo $detailsBo): JsonResponse
    {
        $this->detailsBo = $detailsBo;
        try {
            $user = $this->findUser();
            $this->verifyCurrentPassword($user);
            $this->performUpdate();

            return response()->json(CommonUtils::successResponse('Password changed successfully'));
        } catch (DataNotFoundException|UserNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to change password'));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->userHelper->prepareChangePasswordBo($detailsRequest);
    }

    private function findUser(): Collection
    {
        $user = collect($this->userRepository->findById($this->loggedInUserId)->first());

        if ($user->isEmpty()) {
            throw UserNotFoundException::withMessage('User not found');
        }

        return $user;
    }

    private function verifyCurrentPassword(Collection $user): void
    {
        if (! Hash::check($this->detailsBo->getCurrentPassword(), $user->get('password'))) {
            throw DataNotFoundException::withMessage('Current password is incorrect');
        }
    }

    private function performUpdate(): void
    {
        $userDao = $this->userHelper->prepareChangePasswordDao($this->detailsBo);
        $this->userRepository->updateById($this->loggedInUserId, $userDao);
    }
}
