<?php

namespace App\Http\Requests\V1\Admin\User\List;

use App\Constants\UserConstant;
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
            'role' => 'nullable|string|in:'.UserConstant::USER_ROLE_ADMIN.','.UserConstant::USER_ROLE_SUB_ADMIN.','.UserConstant::USER_ROLE_RECRUITER.','.UserConstant::USER_ROLE_JOB_SEEKER,
            'status' => 'nullable|string|in:active,inactive',
            'search' => 'nullable|string|max:100',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
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

    protected $fields = [
        'role',
        'search',
        'page',
        'per_page',
    ];
}
