<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
         DB::listen(function ($query) {
            Log::info($query->sql, $query->bindings);
        });
        Broadcast::routes(['middleware' => ['api', 'auth.broadcast']]);

        require base_path('routes/channels.php');
    }
}
