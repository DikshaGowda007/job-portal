<?php

namespace App\Modules\V1\User\Services\UpdateProfile;

use App\Constants\CommonConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\User\UpdateProfile\DetailsRequest as UpdateProfileDetailsRequest;
use App\Modules\V1\User\Bo\UpdateProfile\DetailsBo as UpdateProfileDetailsBo;
use App\Modules\V1\User\Helpers\UserHelper;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    private UpdateProfileDetailsBo $updateProfileDetailsBo;

    public function __construct(
        private UserHelper $userHelper,
        private UserRepository $userRepository,
    ) {}

    public function prepareBo(UpdateProfileDetailsRequest $request): UpdateProfileDetailsBo
    {
        return $this->userHelper->prepareUpdateProfileBo($request);
    }

    public function updateProfile(UpdateProfileDetailsBo $bo): JsonResponse
    {
        $this->updateProfileDetailsBo = $bo;
        try {
            $user = $this->findUser();
            $this->validateEmailUniqueness($user);
            $this->performUpdate();

            return response()->json(CommonUtils::successResponse('Profile updated successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to update profile'));
        }
    }

    private function findUser(): Collection
    {
        $user = collect($this->userRepository->findById($this->updateProfileDetailsBo->getUserId())->first());

        if ($user->isEmpty()) {
            throw DataNotFoundException::withMessage('User not found');
        }

        return $user;
    }

    private function validateEmailUniqueness(Collection $user): void
    {
        $newEmail = $this->updateProfileDetailsBo->getEmail();

        if ($newEmail && $newEmail !== $user->get('email')) {
            $existing = $this->userRepository->findByEmail($newEmail);
            if ($existing->isNotEmpty()) {
                throw DataNotFoundException::withMessage('Email already in use');
            }
        }
    }

    private function performUpdate(): void
    {
        $userDao = $this->userHelper->prepareUpdateProfileDao($this->updateProfileDetailsBo);
        $this->userRepository->updateById($this->updateProfileDetailsBo->getUserId(), $userDao);
    }
}
