<?php

namespace App\Modules\V1\User\Helpers;

use App\Http\Requests\V1\User\ChangePassword\DetailsRequest as ChangePasswordDetailsRequest;
use App\Modules\V1\User\Bo\ChangePassword\DetailsBo as ChangePasswordDetailsBo;
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
}
