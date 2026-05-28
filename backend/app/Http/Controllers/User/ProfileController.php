<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\ChangePassword\DetailsRequest as ChangePasswordRequest;
use App\Http\Requests\V1\User\Profile\DetailsRequest as ProfileRequest;
use App\Http\Requests\V1\User\UpdateProfile\DetailsRequest as UpdateProfileRequest;
use App\Modules\V1\User\Services\ChangePassword\DetailsService as ChangePasswordService;
use App\Modules\V1\User\Services\Profile\DetailsService as ProfileService;
use App\Modules\V1\User\Services\UpdateProfile\DetailsService as UpdateProfileService;
use Illuminate\Http\JsonResponse;
use Throwable;

class ProfileController extends Controller
{
    public function me(ProfileRequest $request): JsonResponse
    {
        try {
            $service = app(ProfileService::class);

            return $service->getProfile();
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $service = app(UpdateProfileService::class);
            $updateProfileDetailsBo = $service->prepareBo($request);

            return $service->updateProfile($updateProfileDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function changePassword(ChangePasswordRequest $changePasswordRequest): JsonResponse
    {
        try {
            $changePasswordService = app(ChangePasswordService::class);
            $changePasswordDetailsBo = $changePasswordService->prepareBo($changePasswordRequest);

            return $changePasswordService->changePassword($changePasswordDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
