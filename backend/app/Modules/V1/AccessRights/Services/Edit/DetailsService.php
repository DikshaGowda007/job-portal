<?php

namespace App\Modules\V1\AccessRights\Services\Edit;

use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\UserConstant;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\V1\AccessRights\Edit\DetailsRequest;
use App\Modules\V1\AccessRights\Bo\Edit\DetailsBo;
use App\Repositories\DAO\V1\AllUserAccessRightsDAO;
use App\Repositories\V1\AllUserAccessRightRepository;
use App\Repositories\V1\UserRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private UserRepository $userRepository,
        private AllUserAccessRightRepository $allUserAccessRightRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function edit(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $targetUser = $this->findUser($detailsBo->getUserId());
            $this->ensureTargetRoleIsAllowed($targetUser->user_type);
            $this->ensureNoPrivilegeEscalation($detailsBo->getAccessDetails());
            $this->updateAccessRights($detailsBo->getUserId(), $detailsBo->getAccessDetails());

            return response()->json(CommonUtils::successResponse('Access rights updated successfully'));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_UPDATE_DATA));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        $detailsBo = new DetailsBo;

        $detailsBo->setUserId((int) $detailsRequest->input('user_id'));
        $detailsBo->setAccessDetails($detailsRequest->input('access_details'));

        return $detailsBo;
    }

    private function findUser(int $userId)
    {
        $user = $this->userRepository->findById($userId)->first();
        if (! $user) {
            throw DataNotFoundException::withMessage('User not found');
        }

        return $user;
    }

    private function ensureTargetRoleIsAllowed(int $userType): void
    {
        // Sub-admin can only manage recruiters
        if ($this->loggedInUserRole === UserConstant::USER_ROLE_SUB_ADMIN && $userType !== UserConstant::USER_ROLE_RECRUITER) {
            throw DataNotFoundException::withMessage('Sub-admins can only manage recruiter access rights');
        }

        if (! in_array($userType, [UserConstant::USER_ROLE_SUB_ADMIN, UserConstant::USER_ROLE_RECRUITER])) {
            throw DataNotFoundException::withMessage('Access rights can only be updated for sub-admins and recruiters');
        }
    }

    private function ensureNoPrivilegeEscalation(array $accessDetails): void
    {

        if ($this->loggedInUserRole !== UserConstant::USER_ROLE_SUB_ADMIN) {
            return;
        }

        $myAccessRights = $this->allUserAccessRightRepository->findByUserId($this->loggedInUserId)->first();

        foreach ($accessDetails as $key => $value) {
            if ($value && (! $myAccessRights || ! $myAccessRights->$key)) {
                throw DataNotFoundException::withMessage("You cannot grant '{$key}' permission as you don't have it yourself");
            }
        }
    }

    private function updateAccessRights(int $userId, array $accessDetails): void
    {
        $this->allUserAccessRightRepository->updateByUserId($userId, $this->buildDao($accessDetails));
    }

    private function buildDao(array $accessDetails): AllUserAccessRightsDAO
    {
        return (new AllUserAccessRightsDAO)
            ->setJobView($accessDetails['job_view'] ?? null)
            ->setJobEdit($accessDetails['job_edit'] ?? null)
            ->setJobDelete($accessDetails['job_delete'] ?? null)
            ->setJobPublish($accessDetails['job_publish'] ?? null)
            ->setJobClose($accessDetails['job_close'] ?? null)
            ->setJobApply($accessDetails['job_apply'] ?? null)
            ->setApplicationView($accessDetails['application_view'] ?? null)
            ->setApplicationStatusUpdate($accessDetails['application_status_update'] ?? null)
            ->setApplicationShortlist($accessDetails['application_shortlist'] ?? null)
            ->setApplicationReject($accessDetails['application_reject'] ?? null)
            ->setApplicationWithdraw($accessDetails['application_withdraw'] ?? null)
            ->setApplicationDownloadResume($accessDetails['application_download_resume'] ?? null)
            ->setCompanyProfileView($accessDetails['company_profile_view'] ?? null)
            ->setCompanyProfileEdit($accessDetails['company_profile_edit'] ?? null)
            ->setCategoryView($accessDetails['category_view'] ?? null)
            ->setCategoryAdd($accessDetails['category_add'] ?? null)
            ->setCategoryEdit($accessDetails['category_edit'] ?? null)
            ->setCategoryDelete($accessDetails['category_delete'] ?? null)
            ->setUserEdit($accessDetails['user_edit'] ?? null)
            ->setUserAdd($accessDetails['user_add'] ?? null)
            ->setRoleManage($accessDetails['role_manage'] ?? null);
    }
}
