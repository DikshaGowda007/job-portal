<?php

namespace App\Http\Requests\V1\JobApplication\Apply;

use App\Constants\JobApplicationConstants;
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
        if ($this->accessService->hasJobApplyAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    public function rules(): array
    {
        return [
            'job_post_id' => 'required|integer|exists:job_posts,id',
            'resume_path' => 'nullable|string|max:500',
            'cover_letter' => 'nullable|string|max:5000',
            'expected_salary' => 'nullable|numeric|min:0',
            'expected_salary_currency' => 'nullable|in:'.implode(',', JobApplicationConstants::VALID_CURRENCIES),
            'notice_period' => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0|max:50',
        ];
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
            'job_post_id' => 'Job',
            'resume_path' => 'Resume',
            'cover_letter' => 'Cover Letter',
            'expected_salary' => 'Expected Salary',
            'expected_salary_currency' => 'Salary Currency',
            'notice_period' => 'Notice Period',
            'experience_years' => 'Years of Experience',
        ];
    }
}
