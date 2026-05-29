<?php

namespace App\Modules\Auth\Services;

use App\Repositories\DAO\V1\UserDAO;
use App\Repositories\V1\UserRepository;
use App\Utils\CommonUtils;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LogoutService
{
    public function __construct(private UserRepository $userRepository) {}

    public function logout(Request $request): JsonResponse
    {
        $jwtUser = $request->attributes->get('jwtUser');

        if ($jwtUser && isset($jwtUser['loggedin_user_id'])) {
            $userDao = new UserDAO;
            $userDao->setLastLogin(Carbon::now()->format('Y-m-d H:i:s'));
            $this->userRepository->updateById((int) $jwtUser['loggedin_user_id'], $userDao);
        }

        Cookie::queue(Cookie::forget('token'));

        return response()->json(CommonUtils::successResponse('Logged out successfully'));
    }
}
