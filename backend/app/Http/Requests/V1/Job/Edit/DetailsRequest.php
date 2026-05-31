<?php

namespace App\Http\Requests\V1\Job\Edit;

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
        if ($this->accessService->hasJobEditAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:job_posts,id',
            'company_name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'job_description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:255',
            'salary' => 'sometimes|nullable|numeric|min:0',
            'salary_min' => 'sometimes|nullable|numeric|min:0',
            'salary_max' => 'sometimes|nullable|numeric|gte:salary_min',
            'salary_currency' => 'sometimes|nullable|in:INR,USD',
            'salary_type' => 'sometimes|nullable|in:monthly,yearly',
            'job_category_id' => 'sometimes|nullable|integer|exists:job_categories,id',
            'work_mode' => 'sometimes|required|in:onsite,remote,hybrid',
            'job_type' => 'sometimes|required|in:FULL_TIME,PART_TIME,REMOTE,INTERNSHIP',
            'roles_responsibility' => 'sometimes|nullable|array',
            'roles_responsibility.*' => 'string|max:500',
            'experience_level' => 'sometimes|nullable|in:FRESHER,JUNIOR,MID,SENIOR,TEAM_LEAD',
            'experience_min' => 'sometimes|nullable|integer|min:0',
            'experience_max' => 'sometimes|nullable|integer|gte:experience_min',
            'education' => 'sometimes|nullable|string|max:255',
            'skills' => 'sometimes|nullable|array',
            'skills.*' => 'string|max:100',
            'status' => 'sometimes|required|in:OPEN,CLOSED,EXPIRED,DRAFT',
            'expires_at' => 'sometimes|nullable|date|after:today',
            'openings_count' => 'sometimes|nullable|integer|min:1',
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
            'id' => 'Job ID',
            'company_name' => 'Company Name',
            'title' => 'Job Title',
            'job_description' => 'Job Description',
            'location' => 'Job Location',
            'salary' => 'Salary',
            'salary_min' => 'Minimum Salary',
            'salary_max' => 'Maximum Salary',
            'salary_currency' => 'Salary Currency',
            'salary_type' => 'Salary Type',
            'job_category_id' => 'Job Category',
            'work_mode' => 'Work Mode',
            'job_type' => 'Job Type',
            'roles_responsibility' => 'Roles & Responsibilities',
            'roles_responsibility.*' => 'Role Responsibility',
            'experience_level' => 'Experience Level',
            'experience_min' => 'Minimum Experience',
            'experience_max' => 'Maximum Experience',
            'education' => 'Education',
            'skills' => 'Skills',
            'skills.*' => 'Skill',
            'status' => 'Job Status',
            'expires_at' => 'Expiry Date',
            'openings_count' => 'Number of Openings',
        ];
    }

    protected $fields = [
        'id',
        'company_name',
        'title',
        'job_description',
        'location',
        'salary',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_type',
        'job_category_id',
        'work_mode',
        'job_type',
        'roles_responsibility',
        'experience_level',
        'experience_min',
        'experience_max',
        'education',
        'skills',
        'status',
        'expires_at',
        'openings_count',
    ];
}
