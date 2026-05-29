<?php

namespace App\Modules\V1\User\Helpers;

use App\Http\Requests\V1\User\ChangePassword\DetailsRequest as ChangePasswordDetailsRequest;
use App\Http\Requests\V1\User\UpdateProfile\DetailsRequest as UpdateProfileDetailsRequest;
use App\Modules\V1\User\Bo\ChangePassword\DetailsBo as ChangePasswordDetailsBo;
use App\Modules\V1\User\Bo\UpdateProfile\DetailsBo as UpdateProfileDetailsBo;
use App\Repositories\DAO\V1\UserDAO;
use Illuminate\Support\Facades\Hash;

class UserHelper
{
    public function __construct(
        private UserDAO $userDao,
    ) {}

    public function prepareChangePasswordBo(ChangePasswordDetailsRequest $changePasswordDetailsRequest): ChangePasswordDetailsBo
    {
        $changePasswordDetailsBo = new ChangePasswordDetailsBo;
        $changePasswordDetailsBo->setCurrentPassword($changePasswordDetailsRequest->input('current_password'));
        $changePasswordDetailsBo->setNewPassword($changePasswordDetailsRequest->input('new_password'));

        return $changePasswordDetailsBo;
    }

    public function prepareChangePasswordDao(ChangePasswordDetailsBo $changePasswordDetailsBo): UserDAO
    {
        $this->userDao->setPassword(Hash::make($changePasswordDetailsBo->getNewPassword()));

        return $this->userDao;
    }

    public function prepareUpdateProfileBo(UpdateProfileDetailsRequest $request): UpdateProfileDetailsBo
    {
        $updateProfileDetailsBo = new UpdateProfileDetailsBo;
        $updateProfileDetailsBo->setUserId($this->loggedInUserId);
        $updateProfileDetailsBo->setFirstName($request->input('first_name'));
        $updateProfileDetailsBo->setLastName($request->input('last_name'));
        $updateProfileDetailsBo->setEmail($request->input('email'));

        return $updateProfileDetailsBo;
    }

    public function prepareUpdateProfileDao(UpdateProfileDetailsBo $bo): UserDAO
    {
        if (! empty($bo->getFirstName())) {
            $this->userDao->setFirstName($bo->getFirstName());
        }
        if (! empty($bo->getLastName())) {
            $this->userDao->setLastName($bo->getLastName());
        }
        if (! empty($bo->getEmail())) {
            $this->userDao->setEmail($bo->getEmail());
        }

        return $this->userDao;
    }
}
