<?php

namespace App\Http\Requests\V1\RecruiterProfile\Update;

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
            'company_name' => 'nullable|string|max:200',
            'company_about' => 'nullable|string|max:2000',
            'company_website' => 'nullable|url|max:255',
            'company_size' => 'nullable|string|in:1-10,11-50,51-200,201-500,500+',
            'industry' => 'nullable|string|max:100',
            'company_phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'linkedin_url' => 'nullable|url|max:255',
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
