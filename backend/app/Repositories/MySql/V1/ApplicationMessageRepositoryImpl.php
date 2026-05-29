<?php

namespace App\Repositories\MySql\V1;

use App\Constants\CommonConstant;
use App\Models\ApplicationMessage;
use App\Repositories\DAO\V1\ApplicationMessageDAO;
use App\Repositories\V1\ApplicationMessageRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ApplicationMessageRepositoryImpl implements ApplicationMessageRepository
{
    public function insert(ApplicationMessageDAO $applicationMessageDAO): ApplicationMessage
    {
        $applicationMessageDAO->setCreatedAt(Carbon::now()->format('Y-m-d H:i:s'));

        return ApplicationMessage::create($applicationMessageDAO->toArray());
    }

    public function fetchByUserIdWithJobPostsAndSenderId(int $userId): Collection
    {
        return ApplicationMessage::join('job_applications', 'application_messages.application_id', '=', 'job_applications.id')
            ->join('job_posts', 'job_applications.job_post_id', '=', 'job_posts.id')
            ->where('job_applications.user_id', $userId)
            ->where('job_applications.is_deleted', CommonConstant::IS_DELETED_NO)
            ->select(
                'application_messages.id',
                'application_messages.application_id',
                'application_messages.sender_id',
                'application_messages.message',
                'application_messages.created_at',
                'job_posts.title as job_title',
                'job_posts.company_name'
            )
            ->with(['sender:id,first_name,last_name'])
            ->get();
    }

    public function fetchByRecruiterIdWithJobPostsAndSenderId(int $recruiterId): Collection
    {
        return ApplicationMessage::join('job_applications', 'application_messages.application_id', '=', 'job_applications.id')
            ->join('job_posts', 'job_applications.job_post_id', '=', 'job_posts.id')
            ->join('users as seekers', 'job_applications.user_id', '=', 'seekers.id')
            ->where('job_posts.user_id', $recruiterId)
            ->where('job_applications.is_deleted', CommonConstant::IS_DELETED_NO)
            ->select(
                'application_messages.id',
                'application_messages.application_id',
                'application_messages.sender_id',
                'application_messages.message',
                'application_messages.created_at',
                'job_posts.title as job_title',
                'job_posts.company_name',
                'job_applications.user_id as seeker_id',
                DB::raw("TRIM(CONCAT(seekers.first_name, ' ', seekers.last_name)) as seeker_name"),
            )
            ->with(['sender:id,first_name,last_name'])
            ->get();
    }
}
