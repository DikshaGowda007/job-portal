<?php

namespace App\Modules\V1\JobApplication\Services\UpdateStatus;

use App\Constants\CommonConstant;
use App\Constants\JobApplicationConstants;
use App\Constants\NotificationConstants;
use App\Constants\UserConstant;
use App\Exceptions\AccessForbiddenException;
use App\Exceptions\InvalidDataException;
use App\Http\Requests\V1\JobApplication\UpdateStatus\DetailsRequest;
use App\Mail\ApplicationStatusChangedMail;
use App\Modules\V1\JobApplication\Bo\UpdateStatus\DetailsBo;
use App\Modules\V1\JobApplication\Helpers\JobApplicationHelper;
use App\Repositories\DAO\V1\NotificationDAO;
use App\Repositories\V1\ApplicationMessageRepository;
use App\Repositories\V1\JobApplicationHistoryRepository;
use App\Repositories\V1\JobApplicationRepository;
use App\Repositories\V1\MessageLogRepository;
use App\Repositories\V1\NotificationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class DetailsService
{
    use AccessRightsTrait;

    private string $oldStatus = '';

    public function __construct(
        private JobApplicationHelper $helper,
        private JobApplicationRepository $jobApplicationRepository,
        private JobApplicationHistoryRepository $jobApplicationHistoryRepository,
        private ApplicationMessageRepository $applicationMessageRepository,
        private MessageLogRepository $messageLogRepository,
        private NotificationRepository $notificationRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function updateStatus(DetailsBo $detailsBo): JsonResponse
    {
        try {
            $applicationId = $detailsBo->getApplicationId();
            [$recruiterId, $reviewedByUserId, $application, $seekerUserId] = $this->populateApplicationDetails($applicationId);
            $this->hasAccess($recruiterId, $reviewedByUserId);

            $this->validateStatusTransition($detailsBo->getStatus());
            $this->updateApplicationStatus($detailsBo);
            $this->logApplicationHistory($applicationId, $detailsBo->getStatus(), $detailsBo->getRecruiterNotes());
            $this->saveMessage($applicationId, $seekerUserId, $detailsBo->getRecruiterNotes());
            $this->sendStatusChangeNotification($application, $detailsBo->getStatus(), $detailsBo->getRecruiterNotes());
            $this->createSeekerNotification($seekerUserId, $applicationId, $application, $detailsBo->getStatus());

            return response()->json(CommonUtils::successResponse('Application status updated successfully'));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to update application status'));
        }
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        return $this->helper->prepareUpdateStatusBo($detailsRequest);
    }

    private function populateApplicationDetails(int $applicationId): array
    {
        $applicationDetails = collect($this->jobApplicationRepository->findByIdWithJobPostAndUser($applicationId)->first());
        $this->oldStatus = $applicationDetails->get('status');

        return [
            $applicationDetails->get('job_post')['user_id'],
            $applicationDetails->get('reviewed_by_user_id'),
            $applicationDetails,
            $applicationDetails->get('user')['id'] ?? null,
        ];
    }

    private function hasAccess(int $recruiterId, ?int $reviewedByUserId): void
    {
        if ($this->loggedInUserRole !== UserConstant::USER_ROLE_RECRUITER && $this->loggedInUserId !== $recruiterId && $this->loggedInUserId !== $reviewedByUserId) {
            throw AccessForbiddenException::withMessage();
        }
    }

    private function updateApplicationStatus(DetailsBo $detailsBo): void
    {
        $jobApplicationDao = $this->helper->prepareUpdateStatusDao($detailsBo);
        $this->jobApplicationRepository->updateById($detailsBo->getApplicationId(), $jobApplicationDao);
    }

    private function logApplicationHistory(int $applicationId, string $newStatus, ?string $notes): void
    {
        $historyDao = $this->helper->prepareHistoryDao(
            $applicationId,
            $this->oldStatus,
            $newStatus,
            $this->loggedInActionByUserId,
            $notes ?? 'Status updated from '.$this->oldStatus.' to '.$newStatus
        );
        $this->jobApplicationHistoryRepository->insert($historyDao);
    }

    private function validateStatusTransition(string $newStatus): void
    {
        $validTransitions = [
            JobApplicationConstants::STATUS_PENDING => [
                JobApplicationConstants::STATUS_REVIEWED,
                JobApplicationConstants::STATUS_REJECTED,
                JobApplicationConstants::STATUS_SHORTLISTED,
            ],
            JobApplicationConstants::STATUS_REVIEWED => [
                JobApplicationConstants::STATUS_SHORTLISTED,
                JobApplicationConstants::STATUS_REJECTED,
                JobApplicationConstants::STATUS_INTERVIEW,
            ],
            JobApplicationConstants::STATUS_SHORTLISTED => [
                JobApplicationConstants::STATUS_INTERVIEW,
                JobApplicationConstants::STATUS_REJECTED,
            ],
            JobApplicationConstants::STATUS_INTERVIEW => [
                JobApplicationConstants::STATUS_OFFERED,
                JobApplicationConstants::STATUS_REJECTED,
            ],
            JobApplicationConstants::STATUS_OFFERED => [
                JobApplicationConstants::STATUS_HIRED,
                JobApplicationConstants::STATUS_REJECTED,
            ],
        ];

        if ($this->oldStatus === $newStatus) {
            throw InvalidDataException::withMessage('New status must be different from current status');
        }

        if ($this->oldStatus === JobApplicationConstants::STATUS_HIRED || $this->oldStatus === JobApplicationConstants::STATUS_REJECTED) {
            throw InvalidDataException::withMessage('Cannot change status of a finalized application');
        }

        if (! isset($validTransitions[$this->oldStatus]) || ! in_array($newStatus, $validTransitions[$this->oldStatus])) {
            throw InvalidDataException::withMessage('Invalid status transition from '.$this->oldStatus.' to '.$newStatus);
        }
    }

    private function saveMessage(int $applicationId, ?int $receiverId, ?string $message): void
    {
        if (! $message) {
            return;
        }

        try {
            $msgDao = new \App\Repositories\DAO\V1\ApplicationMessageDAO;
            $msgDao->setApplicationId($applicationId);
            $msgDao->setSenderId($this->loggedInUserId);
            $msgDao->setMessage($message);
            $this->applicationMessageRepository->insert($msgDao);

            $logDao = new \App\Repositories\DAO\V1\MessageLogDAO;
            $logDao->setApplicationId($applicationId);
            $logDao->setSenderId($this->loggedInUserId);
            $logDao->setReceiverId($receiverId);
            $logDao->setMessage($message);
            $logDao->setIsDelivered(1);
            $logDao->setStatus(1);
            $this->messageLogRepository->insert($logDao);
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to save message: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }

    private function sendStatusChangeNotification(Collection $application, string $newStatus, ?string $message = null): void
    {
        try {
            $seekerEmail = $application->get('user')['email'] ?? null;
            $candidateName = trim(($application->get('user')['first_name'] ?? '').' '.($application->get('user')['last_name'] ?? ''));
            $jobTitle = $application->get('job_post')['title'] ?? null;
            $companyName = $application->get('job_post')['company_name'] ?? null;

            if ($seekerEmail && $jobTitle) {
                Mail::to($seekerEmail)->send(new ApplicationStatusChangedMail(
                    $candidateName,
                    $jobTitle,
                    $companyName,
                    $this->oldStatus,
                    $newStatus,
                    $message
                ));
            }
        } catch (\Exception $e) {
            CommonUtils::handleException('Email notification failed: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }

    private function createSeekerNotification(?int $seekerUserId, int $applicationId, Collection $application, string $newStatus): void
    {
        if (! $seekerUserId) {
            return;
        }

        try {
            $jobTitle = $application->get('job_post')['title'] ?? 'your application';
            $companyName = $application->get('job_post')['company_name'] ?? '';
            $displayStatus = ucfirst(strtolower($newStatus));

            $dao = (new NotificationDAO)
                ->setUserId($seekerUserId)
                ->setType(NotificationConstants::TYPE_APPLICATION_STATUS_CHANGED)
                ->setTitle('Application status updated')
                ->setBody("Your application for {$jobTitle}".($companyName ? " at {$companyName}" : '')." is now {$displayStatus}")
                ->setLinkId($applicationId);

            $this->notificationRepository->insert($dao);
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to create seeker notification: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }
}
