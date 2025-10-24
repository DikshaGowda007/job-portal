<?php

namespace App\Http\Services;

use App\Exceptions\TokenExpiredException;
use App\Modules\Auth\JwtService;
use App\Utils\CommonUtils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AuthService
{
    private int $userId;
    private string $userType;
    private string $userRole;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $mobile;

    public function __construct(Request $request)
    {
        $this->initializeUserRoles($request);
    }


    private function initializeUserRoles(Request $request): void
    {
        $authToken = $this->getAuthToken($request);
        try {
            $data = collect(JwtService::decodeToken($authToken));
            $this->userId = $data->get('loggedin_user_id');
            $this->userType = $data->get('loggedin_user_type');
            $this->userRole = $this->userType;
            $this->firstName = $data->get('loggedin_user_first_name');
            $this->lastName = $data->get('loggedin_user_last_name');
            $this->email = $data->get('loggedin_user_email');
            $this->mobile = $data->get('loggedin_user_mobile');
        } catch (TokenExpiredException $e) {
            CommonUtils::handleTokenError('Token has expired');
        } catch (Exception $e) {
            CommonUtils::handleTokenError('Invalid Token');
        }
    }

    private function getAuthToken(Request $request): string
    {
        $authToken = $request->header('Authorization', '');
        return str_replace('Bearer ', '', $authToken);
    }

    public function getData(): Collection
    {
        $fields = collect([]);

        if (isset($this->userId)) {
            $fields->put('userId', $this->userId);
        }
        if (isset($this->userType)) {
            $fields->put('userType', $this->userType);
        }
        if (isset($this->userRole)) {
            $fields->put('userRole', $this->userRole);
        }
        if (isset($this->firstName)) {
            $fields->put('firstName', $this->firstName);
        }
        if (isset($this->lastName)) {
            $fields->put('lastName', $this->lastName);
        }
        if (isset($this->email)) {
            $fields->put('email', $this->email);
        }
        if (isset($this->mobile)) {
            $fields->put('mobile', $this->mobile);
        }
        
        return $fields;
    }
}
