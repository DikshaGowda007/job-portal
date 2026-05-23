<?php

namespace App\Modules\V1\Admin\Services\SubAdmin\List;

use App\Constants\CommonConstant;
use App\Constants\UserConstant;
use App\Http\Requests\V1\Admin\ListSubAdminsRequest;
use App\Modules\V1\Admin\Bo\ListSubAdminsBo;
use App\Modules\V1\Admin\Helpers\AdminHelper;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    private ListSubAdminsBo $listSubAdminsBo;

    public function __construct(
        private AdminHelper $adminHelper,
        private UserRepository $userRepository,
    ) {}

    public function listSubAdmins(ListSubAdminsBo $bo): JsonResponse
    {
        $this->listSubAdminsBo = $bo;
        try {
            $subAdmins = $this->findSubAdmins();
            $total = $subAdmins->count();
            $paginated = $subAdmins->forPage($this->listSubAdminsBo->getPage(), $this->listSubAdminsBo->getPerPage())->values();
            $response = $this->formatResponse($paginated, $total);

            return response()->json(CommonUtils::successDataResponse($response));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_ERROR);

            return response()->json(CommonUtils::errorResponse('Failed to retrieve sub-admins'));
        }
    }

    public function prepareBo(ListSubAdminsRequest $listSubAdminsRequest): ListSubAdminsBo
    {
        return $this->adminHelper->prepareListSubAdminsBo($listSubAdminsRequest);
    }

    private function formatResponse(Collection $data, int $total): array
    {
        return [
            'data' => $this->formatUsers($data),
            'pagination' => [
                'current_page' => $this->listSubAdminsBo->getPage(),
                'last_page' => (int) ceil($total / $this->listSubAdminsBo->getPerPage()),
                'per_page' => $this->listSubAdminsBo->getPerPage(),
                'total' => $total,
            ],
        ];
    }

    private function formatUsers(Collection $users): array
    {
        return $users->map(fn ($user) => [
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'user_type' => $user['user_type'],
            'verified' => $user['verified'],
            'status' => CommonUtils::getUserStatus($user['status']),
            'last_login' => $user['last_login'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at'],
        ])->values()->all();
    }

    private function findSubAdmins(): Collection
    {
        return $this->userRepository->findByUserTypeOrStatusOrFirstNameOrLastNameOrEmail($this->prepareFilters());
    }

    private function prepareFilters(): array
    {
        return [
            'user_type' => UserConstant::USER_ROLE_SUB_ADMIN,
            'status' => $this->listSubAdminsBo->getStatus(),
        ];
    }
}
