<?php

namespace App\Modules\Auth\Helpers;

use App\Http\Requests\V1\User\Add\UserRequest;
use App\Modules\V1\User\Bo\Add\UserDetailsBo;

class UserHelper
{
    public function __construct(
        private UserDetailsBo $userDetailsBo,
    ) {}

    public function prepareBo(UserRequest $userRequest): UserDetailsBo
    {
        $this->userDetailsBo->setFirstName($userRequest->input('first_name'));
        $this->userDetailsBo->setLastName($userRequest->input('last_name'));
        $this->userDetailsBo->setEmail($userRequest->input('email'));
        $this->userDetailsBo->setPassword($userRequest->input('password'));
        $this->userDetailsBo->setUserType((int) $userRequest->input('user_type'));

        return $this->userDetailsBo;
    }
}
