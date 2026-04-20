<?php

namespace App\Modules\V1\JobApplication\Services\View;

use App\Constants\CommonConstant;
use App\Constants\EmailConstant;
use App\Constants\EmailTemplateConstants;
use App\Constants\ErrorResponseConstant;
use App\Constants\JobApplicationConstants;
use App\Constants\UserConstant;
use App\Dto\ApplicationViewedMailDto;
use App\Events\ApplicationViewed;
use App\Exceptions\DataNotFoundException;
use App\Mail\SeekerApplicationViewedMail;
use App\Modules\V1\Email\Bo\TemplateBo;
use App\Modules\V1\Email\Services\TemplateService;
use App\Repositories\DAO\V1\JobApplicationDAO;
use App\Repositories\V1\JobApplicationRepository;
use App\Repositories\V1\UserRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    private ?Collection $application = null;

    public function __construct(
        private JobApplicationRepository $jobApplicationRepository,
        private UserRepository $userRepository,
        private JobApplicationDAO $jobApplicationDAO
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function view(int $applicationId): JsonResponse
    {
        try {
            $this->findApplication($applicationId);
            $wasAlreadyViewed = $this->checkIfAlreadyViewed();
            $this->updateApplicationStatus($applicationId, $wasAlreadyViewed);
            $this->sendViewedNotification($wasAlreadyViewed);

            return response()->json(CommonUtils::statusResponse());
        } catch (DataNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse(ErrorResponseConstant::ERROR_MESSAGE_GENERAL));
        }
    }

    private function findApplication(int $applicationId): void
    {
        $applicationDetails = $this->jobApplicationRepository->findById($applicationId);
        if (! $applicationDetails) {
            throw DataNotFoundException::withMessage('Application not found');
        }

        $this->application = collect($applicationDetails);
    }

    private function checkIfAlreadyViewed(): bool
    {
        $isViewed = $this->application->get('viewed') === CommonConstant::STATUS_ACTIVE;
        $isViewedByRecruiter = $this->isRecruiter($this->application->get('viewed_by_user_id'));
        $isPending = $this->application->get('status') === JobApplicationConstants::STATUS_PENDING;

        return $isViewed && $isViewedByRecruiter && $isPending;
    }

    private function isRecruiter(?int $userId): bool
    {

        if ($userId) {

            $userData = collect($this->userRepository->findById($userId)->first());

            return $userData->get('user_type') === UserConstant::USER_ROLE_RECRUITER;
        }

        return false;
    }

    private function updateApplicationStatus(int $applicationId, bool $wasAlreadyViewed): void
    {
        if (! $wasAlreadyViewed && $this->loggedInUserRole == UserConstant::USER_ROLE_RECRUITER) {
            $this->jobApplicationDAO->setViewed(CommonConstant::STATUS_ACTIVE);
            $this->jobApplicationDAO->setViewedByUserId($this->loggedInUserId);
            $this->jobApplicationDAO->setViewedAt(Carbon::now()->format('Y-m-d H:i:s'));

            $this->jobApplicationRepository->updateById($applicationId, $this->jobApplicationDAO);
        }
    }

    private function sendViewedNotification(bool $wasAlreadyViewed): void
    {
        // Only send email if this is the first time the application is being viewed
        if ($wasAlreadyViewed || $this->loggedInUserRole != UserConstant::USER_ROLE_RECRUITER) {
            return;
        }

        try {
            $templateBo = $this->prepareTemplateBo();
            $emailDto = $this->prepareEmailDto();

            $templateService = app(TemplateService::class);
            $templateService->initiateEmailProcess($templateBo, $emailDto);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function prepareTemplateBo(): TemplateBo
    {
        $templateBo = new TemplateBo;

        $templateBo->setTemplateCode(EmailTemplateConstants::SEEKER_APPLICATION_VIEWED_EMAIL);
        $templateBo->setModuleType(EmailConstant::MODULE_TYPE_QUEUED);
        $templateBo->setEmailId($this->application->get('user')['email']);
        $templateBo->setMailableClass(SeekerApplicationViewedMail::class);
        $templateBo->setMailableDtoClass(ApplicationViewedMailDto::class);
        $templateBo->setEventClass(ApplicationViewed::class);
        $templateBo->setUserId($this->application->get('user')['id']);

        return $templateBo;
    }

    private function prepareEmailDto(): ApplicationViewedMailDto
    {
        $emailDto = new ApplicationViewedMailDto;

        $emailDto->setApplicationId($this->application->get('id'));
        $emailDto->setJobTitle($this->application->get('job_post')['title']);
        $emailDto->setCandidateName($this->application->get('user')['first_name'].' '.$this->application->get('user')['last_name']);
        $emailDto->setCandidateEmail($this->application->get('user')['email']);
        $emailDto->setRecruiterName($this->loggedInUserFirstName.' '.$this->loggedInUserLastName);
        $emailDto->setCompanyName($this->application->get('jobPost')['company_name'] ?? null);

        return $emailDto;
    }
}
