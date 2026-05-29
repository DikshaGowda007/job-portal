<?php

use App\Exceptions\AccessForbiddenException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);
        $middleware->alias([
            'access.role' => \App\Http\Middleware\AuthenticateRoles::class,
            'jwt.verify' => \App\Http\Middleware\VerifyJwt::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AccessForbiddenException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 403);
        });
    })->create();
