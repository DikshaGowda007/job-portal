<?php

namespace App\Http\Requests\V1\JobApplication\MyApplications;

use App\Constants\JobApplicationConstants;
use App\Constants\UserConstant;
use App\Exceptions\AccessForbiddenException;
use App\Services\V1\User\AccessService;
use App\Traits\V1\AccessRightsTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DetailsRequest extends FormRequest
{
    use AccessRightsTrait;

    private AccessService $accessService;

    public function authorize(): bool
    {
        $this->accessService = app(AccessService::class);
        $this->accessService->initializeUserAuth();
        $this->initializeUserAuthorizationData();
        $this->hasAccess();

        return true;
    }

    private function hasAccess(): void
    {
        if ($this->accessService->hasApplicationViewAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    public function rules(): array
    {
        $rules = [
            'status' => 'nullable|in:' . implode(',', JobApplicationConstants::VALID_STATUSES),
        ];

        if ($this->loggedInUserRole != UserConstant::USER_ROLE_JOB_SEEKER) {
            $rules = array_merge($rules, [
                'job_post_id' => 'nullable|integer|exists:job_posts,id',
            ]);
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator): void
    {
        $firstError = $validator->errors()->first();
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $firstError,
        ]));
    }

    public function attributes(): array
    {
        return [
            'status' => 'Status',
        ];
    }
}
