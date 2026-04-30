<?php

namespace App\Modules\V1\JobApplication\Services\UpdateStatus;

use App\Constants\CommonConstant;
use App\Constants\UserConstant;
use App\Exceptions\AccessForbiddenException;
use App\Http\Requests\V1\JobApplication\UpdateStatus\DetailsRequest;
use App\Mail\ApplicationStatusChangedMail;
use App\Models\JobPost;
use App\Models\User;
use App\Modules\V1\JobApplication\Bo\UpdateStatus\DetailsBo;
use App\Modules\V1\JobApplication\Helpers\JobApplicationHelper;
use App\Repositories\DAO\V1\JobApplicationDAO;
use App\Repositories\DAO\V1\JobApplicationHistoryDAO;
use App\Repositories\V1\JobApplicationHistoryRepository;
use App\Repositories\V1\JobApplicationRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private JobApplicationHelper $helper,
        private JobApplicationRepository $jobApplicationRepository,
        private JobApplicationHistoryRepository $jobApplicationHistoryRepository,
        private JobApplicationDAO $jobApplicationDAO,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function updateStatus(DetailsBo $detailsBo): JsonResponse
    {
        try {

            [$recruiterId, $reviewedByUserId] = $this->populateApplicationDetails($detailsBo->getApplicationId());
            $this->hasAccess($recruiterId, $reviewedByUserId);
            $this->updateApplicationStatus($detailsBo);

            // Log status change in history
            // $this->logStatusChange($bo->getApplicationId(), $oldStatus, $bo->getStatus(), $bo->getRecruiterNotes(), $bo->getReviewedByUserId());

            // // Send email notification to job seeker
            // $this->sendStatusChangeNotification($application, $oldStatus, $bo->getStatus());

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

        $applicationDetails = collect($this->jobApplicationRepository->findById($applicationId));

        return [$applicationDetails['job_post']['recruiter']['id'], $applicationDetails->get('reviewed_by_user_id')];
    }

    private function hasAccess(int $recruiterId, ?int $reviewedByUserId): void
    {
        if ($this->loggedInUserRole !== UserConstant::USER_ROLE_RECRUITER && $this->loggedInUserId !== $recruiterId && $this->loggedInUserId !== $reviewedByUserId) {
            throw AccessForbiddenException::withMessage();
        }
    }

    private function updateApplicationStatus(DetailsBo $detailsBo)
    {
        $this->jobApplicationDAO->setStatus($detailsBo->getStatus());
        $this->jobApplicationDAO->setReviewedByUserId($this->loggedInUserId);
        $this->jobApplicationDAO->setRecruiterNotes($detailsBo->getRecruiterNotes());
        $this->jobApplicationDAO->setReviewedAt(Carbon::now()->format('Y-m-d h:i:s'));

        $this->jobApplicationRepository->updateById($detailsBo->getApplicationId(), $this->jobApplicationDAO);
    }

    private function sendStatusChangeNotification($application, string $oldStatus, string $newStatus): void
    {
        try {
            $jobSeeker = User::find($application->user_id);
            $jobPost = JobPost::find($application->job_post_id);

            if ($jobSeeker && $jobSeeker->email && $jobPost) {
                $candidateName = trim($jobSeeker->first_name.' '.$jobSeeker->last_name);

                Mail::to($jobSeeker->email)->send(new ApplicationStatusChangedMail(
                    $candidateName,
                    $jobPost->title,
                    $jobPost->company_name,
                    $oldStatus,
                    $newStatus,
                    null
                ));
            }
        } catch (\Exception $e) {
            CommonUtils::handleException('Email notification failed: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }

    private function logStatusChange(int $applicationId, string $oldStatus, string $newStatus, ?string $notes, ?int $changedBy): void
    {
        try {
            $historyDao = new JobApplicationHistoryDAO;
            $historyDao->setJobApplicationId($applicationId);
            $historyDao->setPreviousStatus($oldStatus);
            $historyDao->setNewStatus($newStatus);
            $historyDao->setChangedBy($changedBy ?? Auth::id());
            $historyDao->setNotes($notes ?? 'Status updated to '.$newStatus);

            $this->jobApplicationHistoryRepository->insert($historyDao);
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to log application history: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }
}
