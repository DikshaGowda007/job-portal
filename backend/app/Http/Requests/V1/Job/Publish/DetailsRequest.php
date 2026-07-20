<?php

namespace App\Http\Requests\V1\Job\Publish;

use App\Exceptions\AccessForbiddenException;
use App\Services\V1\User\AccessService;
use Illuminate\Contracts\Validation\ValidationRule;
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
        if ($this->accessService->hasJobPublishAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // ID-based publish (publish existing draft by id only)
            'id' => 'nullable|integer|exists:job_posts,id',

            // Basic job info — required only when creating a new published job (no id)
            'company_name' => 'required_without:id|nullable|string|max:255',
            'title' => 'required_without:id|nullable|string|max:255',
            'job_description' => 'required_without:id|nullable|string',
            'location' => 'required_without:id|nullable|string|max:255',

            // Salary
            'salary' => 'nullable|numeric|min:0',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'salary_currency' => 'nullable|in:INR,USD',
            'salary_type' => 'nullable|in:monthly,yearly',

            // Category
            'job_category_id' => 'nullable|integer|exists:job_categories,id',

            // Job type & work mode — required only when creating
            'work_mode' => 'required_without:id|nullable|in:onsite,remote,hybrid',
            'job_type' => 'required_without:id|nullable|in:FULL_TIME,PART_TIME,REMOTE,INTERNSHIP',

            // Roles & responsibilities
            'roles_responsibility' => 'nullable|array',
            'roles_responsibility.*' => 'string|max:500',

            // Experience
            'experience_level' => 'nullable|in:FRESHER,JUNIOR,MID,SENIOR,TEAM_LEAD',
            'experience_min' => 'nullable|integer|min:0',
            'experience_max' => 'nullable|integer|gte:experience_min',

            // Education
            'education' => 'nullable|string|max:255',

            // Skills
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',

            // Job lifecycle — status is always forced to OPEN by the service
            'expires_at' => 'nullable|date|after:today',

            // Openings
            'openings_count' => 'nullable|integer|min:1',
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
            'company_name' => 'Company Name',
            'title' => 'Job Title',
            'job_description' => 'Job Description',
            'location' => 'Job Location',

            // Salary
            'salary' => 'Salary',
            'salary_min' => 'Minimum Salary',
            'salary_max' => 'Maximum Salary',
            'salary_currency' => 'Salary Currency',
            'salary_type' => 'Salary Type',

            // Category
            'job_category_id' => 'Job Category',

            // Job type & work mode
            'work_mode' => 'Work Mode',
            'job_type' => 'Job Type',

            // Roles & responsibilities
            'roles_responsibility' => 'Roles & Responsibilities',
            'roles_responsibility.*' => 'Role Responsibility',

            // Experience
            'experience_level' => 'Experience Level',
            'experience_min' => 'Minimum Experience',
            'experience_max' => 'Maximum Experience',

            // Education
            'education' => 'Education',

            // Skills
            'skills' => 'Skills',
            'skills.*' => 'Skill',

            // Job lifecycle
            'status' => 'Job Status',
            'expires_at' => 'Expiry Date',

            // Openings
            'openings_count' => 'Number of Openings',
        ];
    }

    protected $fields = [
        // Basic job info
        'company_name',
        'title',
        'job_description',
        'location',

        // Salary
        'salary',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_type',

        // Category
        'job_category_id',

        // Job type & work mode
        'work_mode',
        'job_type',

        // Roles & responsibilities
        'roles_responsibility',

        // Experience
        'experience_level',
        'experience_min',
        'experience_max',

        // Education
        'education',

        // Skills
        'skills',

        // Job lifecycle
        'status',
        'expires_at',

        // Openings
        'openings_count',
    ];
}
