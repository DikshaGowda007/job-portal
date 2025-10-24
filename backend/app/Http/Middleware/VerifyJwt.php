<?php

namespace App\Http\Middleware;

use App\Constants\CommonConstant;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\TokenExpiredException;
use App\Modules\Auth\JwtService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyJwt
{
    public function handle(Request $request, Closure $next): Response
    {
        try {

            $token = $request->bearerToken() ?? $request->cookie('token');

            if (!$token) {
                return response()->json([
                    'status' => CommonConstant::ERROR,
                    'message' => CommonConstant::TOKEN_NOT_PROVIDED
                ], CommonConstant::UNAUTHORIZED_EXCEPTION_CODE);
            }

            $decoded = JwtService::decodeToken($token);

            if (!$decoded || !is_array($decoded)) {
                return response()->json([
                    'status' => CommonConstant::ERROR,
                    'message' => CommonConstant::UNAUTHORIZED_EXCEPTION_MESSAGE
                ], CommonConstant::UNAUTHORIZED_EXCEPTION_CODE);
            }

            $request->attributes->set('jwtUser', $decoded);

            return $next($request);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => CommonConstant::ERROR,
                'message' => $e->getMessage()
            ], $e->getCode());

        } catch (InvalidTokenException $e) {
            return response()->json([
                'status' => CommonConstant::ERROR,
                'message' => $e->getMessage()
            ], $e->getCode());

        } catch (Exception $e) {
            return response()->json([
                'status' => CommonConstant::ERROR,
                'message' => 'Invalid Token'
            ], 401);
        }
    }
}
