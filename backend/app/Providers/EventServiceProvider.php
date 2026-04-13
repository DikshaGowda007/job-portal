<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\ApplicationWithdrawn;
use App\Listeners\SendWithdrawalNotifications;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ApplicationWithdrawn::class => [
            SendWithdrawalNotifications::class,
        ],
    ];
}