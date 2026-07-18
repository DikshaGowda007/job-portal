<?php

namespace App\Http\Requests\V1\JobAlert\Edit;

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
        if ($this->accessService->hasJobAlertEditAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:job_alerts,id',
            'keyword' => 'sometimes|nullable|string|max:255',
            'location' => 'sometimes|nullable|string|max:255',
            'job_category_id' => 'sometimes|nullable|integer|exists:job_categories,id',
            'job_type' => 'sometimes|nullable|string|in:FULL_TIME,PART_TIME,CONTRACT,INTERNSHIP',
            'work_mode' => 'sometimes|nullable|string|in:onsite,remote,hybrid,ONSITE,REMOTE,HYBRID',
            'experience_level' => 'sometimes|nullable|string|in:FRESHER,JUNIOR,MID,SENIOR,TEAM_LEAD',
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
            'id' => 'Job Alert ID',
            'keyword' => 'Keyword',
            'location' => 'Location',
            'job_category_id' => 'Job Category',
            'job_type' => 'Job Type',
            'work_mode' => 'Work Mode',
            'experience_level' => 'Experience Level',
        ];
    }

    protected $fields = [
        'id',
        'keyword',
        'location',
        'job_category_id',
        'job_type',
        'work_mode',
        'experience_level',
    ];
}
