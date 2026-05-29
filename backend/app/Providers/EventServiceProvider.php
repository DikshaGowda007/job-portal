<?php

namespace App\Providers;

use App\Events\ApplicationWithdrawn;
use App\Listeners\SendWithdrawalNotifications;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ApplicationWithdrawn::class => [
            SendWithdrawalNotifications::class,
        ],
    ];
}
