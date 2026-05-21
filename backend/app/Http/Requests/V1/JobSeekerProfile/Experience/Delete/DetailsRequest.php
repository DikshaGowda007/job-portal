<?php

namespace App\Http\Requests\V1\JobSeekerProfile\Experience\Delete;

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
            'experience_id' => 'required|integer|exists:job_seeker_experiences,id',
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
            'experience_id' => 'Experience',
        ];
    }
}
