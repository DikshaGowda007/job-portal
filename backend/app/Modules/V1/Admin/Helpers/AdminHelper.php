<?php

namespace App\Modules\V1\Admin\Helpers;

use App\Constants\CommonConstant;
use App\Constants\UserConstant;
use App\Http\Requests\V1\Admin\CreateSubAdminRequest;
use App\Http\Requests\V1\Admin\DashboardRequest;
use App\Http\Requests\V1\Admin\ListSubAdminsRequest;
use App\Http\Requests\V1\Admin\ToggleUserStatusRequest;
use App\Http\Requests\V1\Admin\User\List\DetailsRequest as UserListDetailsRequest;
use App\Http\Requests\V1\Admin\ViewUserRequest;
use App\Modules\V1\Admin\Bo\CreateSubAdminBo;
use App\Modules\V1\Admin\Bo\DashboardBo;
use App\Modules\V1\Admin\Bo\ListSubAdminsBo;
use App\Modules\V1\Admin\Bo\ListUsersBo;
use App\Modules\V1\Admin\Bo\ToggleUserStatusBo;
use App\Modules\V1\Admin\Bo\ViewUserBo;
use App\Repositories\DAO\V1\UserDAO;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminHelper
{
    public function prepareDashboardBo(DashboardRequest $dashboardRequest): DashboardBo
    {
        $dashboardBo = new DashboardBo;

        $dashboardBo->setStartDate($dashboardRequest->input('start_date'));
        $dashboardBo->setEndDate($dashboardRequest->input('end_date'));

        return $dashboardBo;
    }

    public function prepareListUsersBo(UserListDetailsRequest $userListDetailsRequest): ListUsersBo
    {
        $listUsersBo = new ListUsersBo;
        $listUsersBo->setRole($userListDetailsRequest->input('role'));
        $listUsersBo->setStatus($userListDetailsRequest->input('status'));
        $listUsersBo->setSearch($userListDetailsRequest->input('search'));
        $listUsersBo->setPage($userListDetailsRequest->input('page', 1));
        $listUsersBo->setPerPage($userListDetailsRequest->input('per_page', 20));

        return $listUsersBo;
    }

    public function prepareViewUserBo(ViewUserRequest $request): ViewUserBo
    {
        $viewUserBo = new ViewUserBo;

        $viewUserBo->setUserId($request->input('user_id'));

        return $viewUserBo;
    }

    public function prepareToggleUserStatusBo(ToggleUserStatusRequest $toggleUserStatusRequest): ToggleUserStatusBo
    {
        $toggleUserStatusBo = new ToggleUserStatusBo;

        $toggleUserStatusBo->setUserId($toggleUserStatusRequest->input('user_id'));
        $toggleUserStatusBo->setStatus($toggleUserStatusRequest->input('status'));
        $toggleUserStatusBo->setReason($toggleUserStatusRequest->input('reason'));

        return $toggleUserStatusBo;
    }

    public function prepareCreateSubAdminBo(CreateSubAdminRequest $createSubAdminRequest): CreateSubAdminBo
    {
        $createSubAdminBo = new CreateSubAdminBo;

        $createSubAdminBo->setEmail($createSubAdminRequest->input('email'));
        $createSubAdminBo->setFirstName($createSubAdminRequest->input('first_name'));
        $createSubAdminBo->setLastName($createSubAdminRequest->input('last_name'));
        $createSubAdminBo->setPhone($createSubAdminRequest->input('phone'));
        $createSubAdminBo->setPassword($createSubAdminRequest->input('password'));

        return $createSubAdminBo;
    }

    public function prepareListSubAdminsBo(ListSubAdminsRequest $listSubAdminsRequest): ListSubAdminsBo
    {
        $listSubAdminsBo = new ListSubAdminsBo;

        $listSubAdminsBo->setStatus($listSubAdminsRequest->input('status'));
        $listSubAdminsBo->setPage($listSubAdminsRequest->input('page', 1));
        $listSubAdminsBo->setPerPage($listSubAdminsRequest->input('per_page', 20));

        return $listSubAdminsBo;
    }

    public function prepareToggleStatusDao(ToggleUserStatusBo $toggleUserStatusBo): UserDAO
    {
        $userDao = new UserDAO;

        $userDao->setStatus((string) ($toggleUserStatusBo->getStatus() === 'active' ? CommonConstant::STATUS_ACTIVE : CommonConstant::STATUS_INACTIVE));

        return $userDao;
    }

    public function prepareCreateSubAdminDao(CreateSubAdminBo $createSubAdminBo): UserDAO
    {
        $userDao = new UserDAO;

        $userDao->setFirstName($createSubAdminBo->getFirstName());
        $userDao->setLastName($createSubAdminBo->getLastName());
        $userDao->setEmail($createSubAdminBo->getEmail());
        $userDao->setPhone($createSubAdminBo->getPhone());
        $userDao->setPassword(Hash::make($createSubAdminBo->getPassword()));
        $userDao->setUserType(UserConstant::USER_ROLE_SUB_ADMIN);
        $userDao->setStatus(CommonConstant::STATUS_ACTIVE);
        $userDao->setEmailVerifiedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return $userDao;
    }
}
