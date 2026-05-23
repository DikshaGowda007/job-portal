<?php

namespace App\Modules\V1\Admin\Services\User\List;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Http\Requests\V1\Admin\User\List\DetailsRequest as UserListDetailsRequest;
use App\Modules\V1\Admin\Bo\ListUsersBo;
use App\Modules\V1\Admin\Helpers\AdminHelper;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    private ListUsersBo $listUsersBo;

    public function __construct(
        private AdminHelper $adminHelper,
        private UserRepository $userRepository,
    ) {}

    public function listUsers(ListUsersBo $listUsersBo): JsonResponse
    {
        $this->listUsersBo = $listUsersBo;
        try {
            $userDetails = $this->findUsers();
            $total = $userDetails->count();
            $paginated = $userDetails->forPage($this->listUsersBo->getPage(), $this->listUsersBo->getPerPage())->values();

            $response = $this->formatResponse($paginated, $total);

            return response()->json(CommonUtils::successDataResponse($response));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_ERROR);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
    }

    public function prepareBo(UserListDetailsRequest $userListDetailsRequest): ListUsersBo
    {
        return $this->adminHelper->prepareListUsersBo($userListDetailsRequest);
    }

    private function findUsers(): Collection
    {
        return $this->userRepository->findByUserTypeOrStatusOrFirstNameOrLstNameOrEmail($this->prepareFilters())
            ->filter(fn ($user) => $user['verified'] == CommonConstant::IS_VERIFIED_USER)
            ->values();
    }

    private function formatResponse(Collection $userData, int $total): array
    {
        return [
            'data' => $this->formatUsers($userData),
            'pagination' => [
                'current_page' => $this->listUsersBo->getPage(),
                'last_page' => (int) ceil($total / $this->listUsersBo->getPerPage()),
                'per_page' => $this->listUsersBo->getPerPage(),
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

    private function prepareFilters(): array
    {
        return [
            'user_type' => $this->listUsersBo->getRole(),
            'status' => $this->listUsersBo->getStatus(),
            'search' => $this->listUsersBo->getSearch(),
        ];
    }
}
