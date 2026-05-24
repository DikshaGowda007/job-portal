<?php

namespace App\Http\Requests\V1\Recruiter\MyApplications;

use App\Constants\JobApplicationConstants;
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

            'job_post_id' => 'nullable|integer|exists:job_posts,id',
            'status' => 'nullable|string|in:'.implode(',', JobApplicationConstants::VALID_STATUSES),

            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to' => 'nullable|date_format:Y-m-d|after_or_equal:date_from',

            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',

            'sort_by' => 'nullable|string|in:created_at,status,experience_years,expected_salary',
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
            'job_post_id' => 'Job post',
            'status' => 'Application status',
            'date_from' => 'Date from',
            'date_to' => 'Date to',
            'page' => 'Page',
            'per_page' => 'Items per page',
            'sort_by' => 'Sort field',
            'sort_order' => 'Sort order',
        ];
    }
}
