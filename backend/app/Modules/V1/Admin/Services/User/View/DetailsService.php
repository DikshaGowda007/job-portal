<?php

namespace App\Modules\V1\Admin\Services\User\View;

use App\Constants\CommonConstant;
use App\Exceptions\UserNotFoundException;
use App\Http\Requests\V1\Admin\ViewUserRequest;
use App\Modules\V1\Admin\Bo\ViewUserBo;
use App\Modules\V1\Admin\Helpers\AdminHelper;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    private ViewUserBo $viewUserBo;

    public function __construct(
        private AdminHelper $adminHelper,
        private UserRepository $userRepository,
    ) {}

    public function viewUser(ViewUserBo $bo): JsonResponse
    {
        $this->viewUserBo = $bo;
        try {
            $userDetails = $this->findUser();

            $response = $this->formatResponse($userDetails);

            return response()->json(CommonUtils::successDataResponse([
                'data' => $response,
            ]));
        } catch (UserNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_ERROR);

            return response()->json(CommonUtils::errorResponse('Failed to retrieve user details'));
        }
    }

    public function prepareBo(ViewUserRequest $viewUserRequest): ViewUserBo
    {
        return $this->adminHelper->prepareViewUserBo($viewUserRequest);
    }

    private function findUser(): Collection
    {
        $userDetails = collect($this->userRepository->findById($this->viewUserBo->getUserId())->first());

        if ($userDetails->isEmpty()) {
            throw UserNotFoundException::withMessage();
        }

        return $userDetails;
    }

    private function formatResponse(Collection $userDetails): array
    {
        return [
            'id' => $userDetails->get('id'),
            'first_name' => $userDetails->get('first_name'),
            'last_name' => $userDetails->get('last_name'),
            'email' => $userDetails->get('email'),
            'phone' => $userDetails->get('phone'),
            'user_type' => $userDetails->get('user_type'),
            'verified' => $userDetails->get('verified'),
            'status' => CommonUtils::getUserStatus((int) $userDetails->get('status')),
            'last_login' => $userDetails->get('last_login'),
            'created_at' => $userDetails->get('created_at'),
            'updated_at' => $userDetails->get('updated_at'),
        ];
    }
}
