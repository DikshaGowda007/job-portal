<?php

namespace App\Http\Requests\V1\Recruiter\CandidateSearch;

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

            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',

            'location' => 'nullable|string|max:255',

            'experience_min' => 'nullable|numeric|min:0',
            'experience_max' => 'nullable|numeric|min:0',

            'current_job_title' => 'nullable|string|max:255',

            'work_mode' => 'nullable|array',
            'work_mode.*' => 'string|in:ONSITE,REMOTE,HYBRID',

            'job_type' => 'nullable|array',
            'job_type.*' => 'string|in:FULL_TIME,PART_TIME,CONTRACT,INTERNSHIP',

            'notice_period' => 'nullable|string|max:100',
            'immediate_joiner' => 'nullable|boolean',

            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',

            'sort_by' => 'nullable|string|in:updated_at,total_experience_years,profile_completeness',
            'sort_order' => 'nullable|string|in:asc,desc',
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
            'skills' => 'Skills',
            'location' => 'Location',
            'experience_min' => 'Minimum experience',
            'experience_max' => 'Maximum experience',
            'current_job_title' => 'Current job title',
            'work_mode' => 'Work mode',
            'job_type' => 'Job type',
            'notice_period' => 'Notice period',
            'immediate_joiner' => 'Immediate joiner',
            'page' => 'Page',
            'per_page' => 'Items per page',
            'sort_by' => 'Sort field',
            'sort_order' => 'Sort order',
        ];
    }
}
