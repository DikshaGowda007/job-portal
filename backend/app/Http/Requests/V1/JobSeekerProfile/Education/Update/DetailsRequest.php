<?php

namespace App\Http\Requests\V1\JobSeekerProfile\Education\Update;

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
            'education_id' => 'required|integer|exists:job_seeker_education,id',
            'degree' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_year' => 'nullable|integer|min:1900|max:2100',
            'end_year' => 'nullable|integer|min:1900|max:2100',
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
            'education_id' => 'Education',
        ];
    }
}
