<?php

use App\Providers\AppServiceProvider;
use App\Providers\GateServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\RouteServiceProvider;

$providers = [
    AppServiceProvider::class,
    GateServiceProvider::class,
    RepositoryServiceProvider::class,
    RouteServiceProvider::class,
];

if (env('APP_ENV') === 'local') {
    $providers[] = App\Providers\TelescopeServiceProvider::class;
}

return $providers;
