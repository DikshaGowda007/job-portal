<?php

namespace App\Http\Requests\V1\JobAlert\Add;

use App\Exceptions\AccessForbiddenException;
use App\Services\V1\User\AccessService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DetailsRequest extends FormRequest
{
    private AccessService $accessService;

    public function authorize(): bool
    {
        $this->accessService = app(AccessService::class);
        $this->accessService->initializeUserAuth();
        $this->hasAccess();

        return true;
    }

    private function hasAccess(): void
    {
        if ($this->accessService->hasJobAlertAddAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    public function rules(): array
    {
        return [
            'keyword' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'job_category_id' => 'nullable|integer|exists:job_categories,id',
            'job_type' => 'nullable|string|in:FULL_TIME,PART_TIME,CONTRACT,INTERNSHIP',
            'work_mode' => 'nullable|string|in:onsite,remote,hybrid,ONSITE,REMOTE,HYBRID',
            'experience_level' => 'nullable|string|in:FRESHER,JUNIOR,MID,SENIOR,TEAM_LEAD',
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
            'keyword' => 'Keyword',
            'location' => 'Location',
            'job_category_id' => 'Job Category',
            'job_type' => 'Job Type',
            'work_mode' => 'Work Mode',
            'experience_level' => 'Experience Level',
        ];
    }

    protected $fields = [
        'keyword',
        'location',
        'job_category_id',
        'job_type',
        'work_mode',
        'experience_level',
    ];
}
