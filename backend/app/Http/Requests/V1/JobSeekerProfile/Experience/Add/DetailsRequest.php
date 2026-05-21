<?php

namespace App\Http\Requests\V1\JobSeekerProfile\Experience\Add;

use App\Constants\JobSeekerProfileConstants;
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
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'employment_type' => 'nullable|string|in:'.implode(',', JobSeekerProfileConstants::VALID_EMPLOYMENT_TYPES),
            'location' => 'nullable|string|max:255',
            'work_mode' => 'nullable|string|in:'.implode(',', JobSeekerProfileConstants::VALID_WORK_MODES),
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'nullable|boolean',
            'description' => 'nullable|string|max:5000',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
        ]));
    }

    public function attributes(): array
    {
        return [
            'job_title' => 'Job Title',
            'company_name' => 'Company Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }
}
