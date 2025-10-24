<?php

namespace App\Http\Services;

use App\Constants\UserConstant;
use App\Repositories\V1\AllUserAccessRightRepository;
use App\Repositories\V1\UserRepository;
use Illuminate\Support\Collection;

class AccessRightsService
{
    private array $userAccess = [];

    private array $userAccessDetails = [];

    private Collection $userData;

    public function __construct(
        private UserRepository $userRepository,
        private AllUserAccessRightRepository $allUserAccessRightRepository,
        private AccessControlInitializer $accessControlInitializer,
    ) {}

    public function getAcessRights(Collection $userData): void
    {
        $this->userData = $userData;
        $userRole = $this->userData->get('userRole');

        if (in_array($userRole, [UserConstant::USER_ROLE_SUB_ADMIN])) {
            $accessRightsData = $this->allUserAccessRightRepository->findByUserId($this->userData->get('userId'));
            if ($accessRightsData->isNotEmpty()) {
                $this->userAccess = $accessRightsData->first()->toArray();
            }
        }
    }

    public function getAccess(): array
    {
        $userRole = $this->userData->get('userRole');
        $this->userAccessDetails = $this->accessControlInitializer->initializeAccess();
        match ((int) $userRole) {
            UserConstant::USER_ROLE_ADMIN => $this->grantAdminAccess(),
            UserConstant::USER_ROLE_SUB_ADMIN => $this->grantSubAdminAccess(),
            UserConstant::USER_ROLE_RECRUITER => $this->grantRecruiterAccess(),
            UserConstant::USER_ROLE_JOB_SEEKER => $this->grantJobSeekerAccess(),
            default => null,
        };
        return [
            'userAccessDetails' => $this->userAccessDetails,
        ];
    }

    private function grantAdminAccess(): void
    {
        foreach ($this->userAccessDetails as $key => $value) {
            $this->userAccessDetails[$key] = 1;
        }
    }

    private function grantSubAdminAccess(): void
    {
        foreach (array_keys($this->userAccessDetails) as $key) {
            $this->userAccessDetails[$key] = $this->userAccess[$key] ?? 0;
        }
    }

    private function grantRecruiterAccess(): void
    {
    }

    private function grantJobSeekerAccess(): void
    {
    }
}