<?php

namespace App\Http\Requests\V1\Recruiter\MyJobs;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => 'nullable|string|max:255',

            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',

            'sort_by' => 'nullable|string|in:created_at,salary_min,salary_max,experience_min,title,company_name',
            'sort_order' => 'nullable|string|in:asc,desc',

            'status' => 'nullable|string|in:OPEN,CLOSED,EXPIRED,DRAFT',

            'work_mode' => 'nullable|array',
            'work_mode.*' => 'string|in:ONSITE,REMOTE,HYBRID',

            'job_type' => 'nullable|array',
            'job_type.*' => 'string|in:FULL_TIME,PART_TIME,CONTRACT,INTERNSHIP',

            'experience_level' => 'nullable|array',
            'experience_level.*' => 'string|in:FRESHER,JUNIOR,MID,SENIOR,TEAM_LEAD',

            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',

            'location' => 'nullable|string|max:255',
            'job_category_id' => 'nullable|integer|exists:job_categories,id',
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
            'text' => 'Search text',
            'page' => 'Page',
            'per_page' => 'Items per page',
            'sort_by' => 'Sort field',
            'sort_order' => 'Sort order',
            'status' => 'Status',
            'work_mode' => 'Work mode',
            'job_type' => 'Job type',
            'experience_level' => 'Experience level',
            'salary_min' => 'Minimum salary',
            'salary_max' => 'Maximum salary',
            'location' => 'Location',
            'job_category_id' => 'Job category',
        ];
    }
}
