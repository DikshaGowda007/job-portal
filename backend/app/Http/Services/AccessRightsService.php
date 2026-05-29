<?php

namespace App\Http\Services;

use App\Constants\UserConstant;
use App\Repositories\V1\AllUserAccessRightRepository;
use Illuminate\Support\Collection;

class AccessRightsService
{
    private array $userAccess = [];

    private array $userAccessDetails = [];

    private Collection $userData;

    public function __construct(
        private AllUserAccessRightRepository $allUserAccessRightRepository,
        private AccessControlInitializer $accessControlInitializer,
    ) {}

    public function getAcessRights(Collection $userData): void
    {
        $this->userData = $userData;
        $userRole = $this->userData->get('userRole');

        if (in_array($userRole, [UserConstant::USER_ROLE_SUB_ADMIN, UserConstant::USER_ROLE_RECRUITER])) {
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

        $this->userAccessDetails['job_view'] = 1;
    }

    private function grantRecruiterAccess(): void
    {
        foreach (array_keys($this->userAccessDetails) as $key) {
            $this->userAccessDetails[$key] = $this->userAccess[$key] ?? 0;
        }
        $this->userAccessDetails['job_view'] = 1;
        $this->userAccessDetails['job_edit'] = 1;
        $this->userAccessDetails['job_delete'] = 1;
        $this->userAccessDetails['job_publish'] = 1;
        $this->userAccessDetails['job_close'] = 1;
        $this->userAccessDetails['application_view'] = 1;
        $this->userAccessDetails['application_status_update'] = 1;
        $this->userAccessDetails['application_shortlist'] = 1;
        $this->userAccessDetails['application_reject'] = 1;
        $this->userAccessDetails['application_download_resume'] = 1;
        $this->userAccessDetails['company_profile_view'] = 1;
        $this->userAccessDetails['company_profile_edit'] = 1;
    }

    private function grantJobSeekerAccess(): void
    {
        $this->userAccessDetails['job_view'] = 1;
        $this->userAccessDetails['job_apply'] = 1;
        $this->userAccessDetails['application_view'] = 1;
        $this->userAccessDetails['application_withdraw'] = 1;
        $this->userAccessDetails['saved_job_list'] = 1;
        $this->userAccessDetails['saved_job_add'] = 1;
        $this->userAccessDetails['saved_job_delete'] = 1;
    }
}
