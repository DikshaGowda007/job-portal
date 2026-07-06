<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Modules\Auth\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateForBroadcast
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $decoded = JwtService::decodeToken($token);

        if (! $decoded || empty($decoded['loggedin_user_id'])) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::find($decoded['loggedin_user_id']);

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
