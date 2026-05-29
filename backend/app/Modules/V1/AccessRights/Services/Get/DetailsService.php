<?php

namespace App\Modules\V1\AccessRights\Services\Get;

use App\Constants\AccessControlConstants;
use App\Constants\CommonConstant;
use App\Constants\ErrorResponseConstant;
use App\Constants\UserConstant;
use App\Exceptions\DataNotFoundException;
use App\Repositories\V1\AllUserAccessRightRepository;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;

class DetailsService
{
    private const PERMISSION_GROUPS = [
        'Job Management' => [
            AccessControlConstants::JOB_VIEW => 'View Jobs',
            AccessControlConstants::JOB_EDIT => 'Edit Jobs',
            AccessControlConstants::JOB_DELETE => 'Delete Jobs',
            AccessControlConstants::JOB_PUBLISH => 'Publish Jobs',
            AccessControlConstants::JOB_CLOSE => 'Close Jobs',
        ],
        'Application Management' => [
            AccessControlConstants::APPLICATION_VIEW => 'View Applications',
            AccessControlConstants::APPLICATION_STATUS_UPDATE => 'Update Status',
            AccessControlConstants::APPLICATION_SHORTLIST => 'Shortlist',
            AccessControlConstants::APPLICATION_REJECT => 'Reject',
            AccessControlConstants::APPLICATION_DOWNLOAD_RESUME => 'Download Resume',
        ],
        'Company Profile' => [
            AccessControlConstants::COMPANY_PROFILE_VIEW => 'View Profile',
            AccessControlConstants::COMPANY_PROFILE_EDIT => 'Edit Profile',
        ],
        'Category Management' => [
            AccessControlConstants::CATEGORY_VIEW => 'View Categories',
            AccessControlConstants::CATEGORY_ADD => 'Add Categories',
            AccessControlConstants::CATEGORY_EDIT => 'Edit Categories',
            AccessControlConstants::CATEGORY_DELETE => 'Delete Categories',
        ],
        'Admin' => [
            AccessControlConstants::USER_EDIT => 'Edit Users',
            AccessControlConstants::USER_ADD => 'Add Users',
            AccessControlConstants::ROLE_MANAGE => 'Manage Roles',
        ],
    ];

    public function __construct(
        private UserRepository $userRepository,
        private AllUserAccessRightRepository $allUserAccessRightRepository,
    ) {}

    public function get(int $userId): JsonResponse
    {
        try {
            $user = $this->findUser($userId);
            $this->ensureTargetRoleIsAllowed($user->user_type);
            $accessRights = $this->findAccessRights($userId);

            return response()->json(CommonUtils::successDataResponse($this->formatResponse($user, $accessRights)));
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_FETCH_DATA));
        }
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
        if (! in_array($userType, [UserConstant::USER_ROLE_SUB_ADMIN, UserConstant::USER_ROLE_RECRUITER])) {
            throw DataNotFoundException::withMessage('Access rights can only be viewed for sub-admins and recruiters');
        }
    }

    private function findAccessRights(int $userId)
    {
        return $this->allUserAccessRightRepository->findByUserId($userId)->first();
    }

    private function formatResponse($user, $accessRights): array
    {
        $groups = [];
        foreach (self::PERMISSION_GROUPS as $groupName => $permissions) {
            $items = [];
            foreach ($permissions as $key => $title) {
                $items[] = [
                    'key' => $key,
                    'title' => $title,
                    'is_enabled' => $accessRights ? (bool) $accessRights->$key : false,
                ];
            }
            $groups[] = [
                'group' => $groupName,
                'permissions' => $items,
            ];
        }

        return [
            'user_id' => $user->id,
            'name' => trim($user->first_name.' '.$user->last_name),
            'email' => $user->email,
            'role' => $user->user_type,
            'groups' => $groups,
        ];
    }
}
