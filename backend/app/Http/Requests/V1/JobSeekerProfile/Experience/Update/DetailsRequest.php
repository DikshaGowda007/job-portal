<?php

namespace App\Http\Requests\V1\JobSeekerProfile\Experience\Update;

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
            'experience_id'   => 'required|integer|exists:job_seeker_experiences,id',
            'job_title'       => 'nullable|string|max:255',
            'company_name'    => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|in:'.implode(',', JobSeekerProfileConstants::VALID_EMPLOYMENT_TYPES),
            'location'        => 'nullable|string|max:255',
            'work_mode'       => 'nullable|string|in:'.implode(',', JobSeekerProfileConstants::VALID_WORK_MODES),
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date',
            'is_current'      => 'nullable|boolean',
            'description'     => 'nullable|string|max:5000',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'error',
            'message' => $validator->errors()->first(),
        ]));
    }

    public function attributes(): array
    {
        return [
            'experience_id' => 'Experience',
        ];
    }
}
