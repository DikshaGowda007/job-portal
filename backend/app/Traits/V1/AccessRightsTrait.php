<?php

namespace App\Traits\V1;

use App\Http\Services\AccessRightsService;
use App\Http\Services\AuthService;
use Illuminate\Support\Collection;

trait AccessRightsTrait
{
    private int $loggedInUserId = 0;

    private int $loggedInUserRole = 0;

    private string $loggedInUserFirstName = '';

    private string $loggedInUserLastName = '';

    private array $loggedInUserAccessDetails;

    private int $loggedInActionByUserId = 0;

    public function initializeUserAuthorizationData(): void
    {
        $authService = app(AuthService::class);

        $userData = $authService->getData();
        $this->setUserVariables($userData);
    }

    private function setUserVariables(Collection $userData): void
    {
        $accessRightsService = app(AccessRightsService::class);
        $this->loggedInUserId = $userData->get('userId');
        if ($this->loggedInUserId == '' || $this->loggedInUserId == null) {
            $this->loggedInUserId = 0;
        }
        $this->loggedInUserRole = $userData->get('userRole');
        $this->loggedInUserFirstName = $userData->get('firstName');
        $this->loggedInUserLastName = $userData->get('lastName');
        $this->loggedInActionByUserId = $this->loggedInUserId;
        $accessRightsService->getAcessRights($userData);
        $userAccessRightsArray = $accessRightsService->getAccess();
        $this->loggedInUserAccessDetails = $userAccessRightsArray['userAccessDetails'];
    }
}
