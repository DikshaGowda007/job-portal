<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Services\AuthService;

class AuthenticateRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $authService = app(AuthService::class);
        $rolesArray = explode('|', $roles);
        if ($authService->getData() !== null) {
            $authData = $authService->getData();
            if ($authData['userRole'] && in_array($authData['userRole'], $rolesArray)) {
                $request->attributes->set('auth_data', $authData);
                return $next($request);
            }
        }

        $request->attributes->set('auth_failed', true);
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    }
}
