<?php

namespace App\Modules\Auth\Services;

use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class LogoutService
{
    public function logout(): JsonResponse
    {
        Cookie::queue(Cookie::forget('token'));

        return response()->json(CommonUtils::successResponse('Logged out successfully'));
    }
}
