<?php

namespace App\Console\Commands;

use App\Constants\CommonConstant;
use App\Constants\JobConstants;
use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireJobsCommand extends Command
{
    protected $signature = 'jobs:expire';

    protected $description = 'Mark open jobs as expired when their expires_at date has passed';

    public function handle(): int
    {
        $count = JobPost::where('status', JobConstants::STATUS_OPEN)
            ->where('is_deleted', CommonConstant::IS_DELETED_NO)
            ->where('expires_at', '<', Carbon::now())
            ->whereNotNull('expires_at')
            ->update([
                'status' => JobConstants::STATUS_EXPIRED,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

        $this->info("Expired {$count} job(s).");

        return Command::SUCCESS;
    }
}
