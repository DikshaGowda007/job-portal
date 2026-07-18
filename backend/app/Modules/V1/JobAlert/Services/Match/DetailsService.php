<?php

namespace App\Modules\V1\JobAlert\Services\Match;

use App\Constants\CommonConstant;
use App\Constants\NotificationConstants;
use App\Mail\JobAlertMatchMail;
use App\Models\JobAlert;
use App\Models\JobPost;
use App\Repositories\DAO\V1\NotificationDAO;
use App\Repositories\V1\JobAlertRepository;
use App\Repositories\V1\NotificationRepository;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Illuminate\Support\Facades\Mail;

class DetailsService
{
    public function __construct(
        private JobAlertRepository $jobAlertRepository,
        private NotificationRepository $notificationRepository,
        private UserRepository $userRepository,
    ) {}

    public function notifyMatchingAlerts(JobPost $job): void
    {
        try {
            $alerts = $this->jobAlertRepository->findAllActive();

            foreach ($alerts as $alert) {
                if ($this->matches($alert, $job)) {
                    $this->createSeekerNotification($alert, $job);
                    $this->sendSeekerEmail($alert, $job);
                }
            }
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to process job alert matches: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }

    private function matches(JobAlert $alert, JobPost $job): bool
    {
        if ($alert->keyword && ! $this->keywordMatches($alert->keyword, $job)) {
            return false;
        }

        if ($alert->location && ! str_contains(strtolower((string) $job->location), strtolower($alert->location))) {
            return false;
        }

        if ($alert->job_category_id && (int) $alert->job_category_id !== (int) $job->job_category_id) {
            return false;
        }

        if ($alert->job_type && $alert->job_type !== $job->job_type) {
            return false;
        }

        if ($alert->work_mode && strtoupper($alert->work_mode) !== strtoupper((string) $job->work_mode)) {
            return false;
        }

        if ($alert->experience_level && $alert->experience_level !== $job->experience_level) {
            return false;
        }

        return true;
    }

    private function keywordMatches(string $keyword, JobPost $job): bool
    {
        $keyword = strtolower($keyword);

        foreach ([$job->title, $job->company_name, $job->location, $job->skills] as $field) {
            if ($field && str_contains(strtolower($field), $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function createSeekerNotification(JobAlert $alert, JobPost $job): void
    {
        try {
            $dao = (new NotificationDAO)
                ->setUserId($alert->user_id)
                ->setType(NotificationConstants::TYPE_JOB_ALERT_MATCH)
                ->setTitle('New job matches your alert')
                ->setBody("{$job->title} at {$job->company_name}")
                ->setLinkId($job->id);

            $this->notificationRepository->insert($dao);
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to create job alert notification: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }

    private function sendSeekerEmail(JobAlert $alert, JobPost $job): void
    {
        try {
            $seeker = collect($this->userRepository->findById($alert->user_id)->first());
            $email = $seeker->get('email');
            if (! $email) {
                return;
            }

            $seekerName = trim($seeker->get('first_name').' '.$seeker->get('last_name')) ?: 'there';

            Mail::to($email)->send(new JobAlertMatchMail(
                $seekerName,
                $job->title,
                $job->company_name,
                $job->id
            ));
        } catch (\Exception $e) {
            CommonUtils::handleException('Failed to send job alert email: '.$e->getMessage(), $e, CommonConstant::LOG_LEVEL_WARNING);
        }
    }
}
