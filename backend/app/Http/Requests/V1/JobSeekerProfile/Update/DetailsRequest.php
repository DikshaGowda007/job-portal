<?php

namespace App\Http\Requests\V1\JobSeekerProfile\Update;

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
            'headline' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:5000',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other,prefer_not_to_say',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'current_job_title' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'total_experience_years' => 'nullable|integer|min:0',
            'total_experience_months' => 'nullable|integer|min:0|max:11',
            'expected_salary' => 'nullable|numeric|min:0',
            'expected_salary_currency' => 'nullable|string|in:INR,USD,EUR,GBP',
            'current_salary' => 'nullable|numeric|min:0',
            'current_salary_currency' => 'nullable|string|in:INR,USD,EUR,GBP',
            'preferred_job_types' => 'nullable|array',
            'preferred_work_modes' => 'nullable|array',
            'preferred_locations' => 'nullable|array',
            'notice_period' => 'nullable|integer|min:0',
            'immediate_joiner' => 'nullable|boolean',
            'skills' => 'nullable|array',
            'linkedin_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'portfolio_url' => 'nullable|url|max:500',
            'is_public' => 'nullable|boolean',
            'open_to_opportunities' => 'nullable|boolean',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ]));
    }
}
