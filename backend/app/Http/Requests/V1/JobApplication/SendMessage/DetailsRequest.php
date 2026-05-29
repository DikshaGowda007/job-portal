<?php

namespace App\Http\Requests\V1\JobApplication\SendMessage;

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
            'application_id' => 'required|integer|exists:job_applications,id',
            'message' => 'required|string|max:2000',
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
            'application_id' => 'Application',
            'message' => 'Message',
        ];
    }
}
