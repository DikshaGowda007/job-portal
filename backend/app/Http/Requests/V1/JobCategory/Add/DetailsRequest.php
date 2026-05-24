<?php

namespace App\Http\Requests\V1\JobCategory\Add;

use App\Exceptions\AccessForbiddenException;
use App\Services\V1\User\AccessService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DetailsRequest extends FormRequest
{
    private AccessService $accessService;

    public function authorize(): bool
    {
        $this->accessService = app(AccessService::class);
        $this->accessService->initializeUserAuth();
        $this->hasAccess();

        return true;
    }

    private function hasAccess(): void
    {
        if ($this->accessService->hasJobCategoryAddAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:job_categories,name',
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
            'name' => 'Category name',
        ];
    }

    protected $fields = ['name'];
}
