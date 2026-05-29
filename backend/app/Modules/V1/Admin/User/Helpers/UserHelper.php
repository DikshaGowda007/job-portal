<?php

namespace App\Modules\V1\Admin\User\Helpers;

use App\Http\Requests\V1\Admin\User\List\DetailsRequest as UserListDetailsRequest;
use App\Modules\V1\Admin\User\Bo\List\DetailsBo as UserListDetailsBo;

class UserHelper
{
    public function prepareUserListBo(UserListDetailsRequest $userListDetailsRequest)
    {
        $userListDetailsBo = new UserListDetailsBo;

        $userListDetailsBo->setRole($userListDetailsRequest->input('role'));
        $userListDetailsBo->setStatus($userListDetailsRequest->input('status'));
        $userListDetailsBo->setSearch($userListDetailsRequest->input('search'));
        $userListDetailsBo->setPage($userListDetailsRequest->input('page', 1));
        $userListDetailsBo->setPerPage($userListDetailsRequest->input('per_page', 20));

        return $userListDetailsBo;
    }
}
