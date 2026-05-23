<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\CreateSubAdminRequest;
use App\Http\Requests\V1\Admin\DashboardRequest;
use App\Http\Requests\V1\Admin\ListSubAdminsRequest;
use App\Http\Requests\V1\Admin\ToggleUserStatusRequest;
use App\Http\Requests\V1\Admin\User\List\DetailsRequest as UserListDetailsRequest;
use App\Http\Requests\V1\Admin\ViewUserRequest;
use App\Modules\V1\Admin\Services\Dashboard\DetailsService as DashboardDetailsService;
use App\Modules\V1\Admin\Services\SubAdmin\Create\DetailsService as CreateSubAdminDetailsService;
use App\Modules\V1\Admin\Services\SubAdmin\List\DetailsService as ListSubAdminsDetailsService;
use App\Modules\V1\Admin\Services\User\List\DetailsService as UserListDetailsService;
use App\Modules\V1\Admin\Services\User\ToggleStatus\DetailsService as ToggleStatusDetailsService;
use App\Modules\V1\Admin\Services\User\View\DetailsService as ViewUserDetailsService;
use Illuminate\Http\JsonResponse;
use Throwable;

class AdminController extends Controller
{
    public function dashboard(DashboardRequest $dashboardRequest): JsonResponse
    {
        try {
            $dashboardDetailsService = app(DashboardDetailsService::class);
            $dashboardDetailsBo = $dashboardDetailsService->prepareBo($dashboardRequest);

            return $dashboardDetailsService->getDashboardStats($dashboardDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function listUsers(UserListDetailsRequest $userListDetailsRequest): JsonResponse
    {
        try {
            $userListDetailsService = app(UserListDetailsService::class);
            $userListDetailsBo = $userListDetailsService->prepareBo($userListDetailsRequest);

            return $userListDetailsService->listUsers($userListDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function viewUser(ViewUserRequest $viewUserRequest): JsonResponse
    {
        try {
            $viewUserDetailsService = app(ViewUserDetailsService::class);
            $viewUserDetailsBo = $viewUserDetailsService->prepareBo($viewUserRequest);

            return $viewUserDetailsService->viewUser($viewUserDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function toggleUserStatus(ToggleUserStatusRequest $toggleUserStatusRequest): JsonResponse
    {
        try {
            $toggleStatusDetailsService = app(ToggleStatusDetailsService::class);
            $toggleStatusDetailsBo = $toggleStatusDetailsService->prepareBo($toggleUserStatusRequest);

            return $toggleStatusDetailsService->toggleUserStatus($toggleStatusDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function createSubAdmin(CreateSubAdminRequest $createSubAdminRequest): JsonResponse
    {
        try {
            $createSubAdminDetailsService = app(CreateSubAdminDetailsService::class);
            $createSubAdminDetailsBo = $createSubAdminDetailsService->prepareBo($createSubAdminRequest);

            return $createSubAdminDetailsService->createSubAdmin($createSubAdminDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    public function listSubAdmins(ListSubAdminsRequest $listSubAdminsRequest): JsonResponse
    {
        try {
            $listSubAdminsDetailsService = app(ListSubAdminsDetailsService::class);
            $listSubAdminsDetailsBo = $listSubAdminsDetailsService->prepareBo($listSubAdminsRequest);

            return $listSubAdminsDetailsService->listSubAdmins($listSubAdminsDetailsBo);
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
