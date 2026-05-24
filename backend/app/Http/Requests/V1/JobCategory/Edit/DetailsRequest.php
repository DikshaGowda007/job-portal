<?php

namespace App\Http\Requests\V1\JobCategory\Edit;

use App\Exceptions\AccessForbiddenException;
use App\Services\V1\User\AccessService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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
        if ($this->accessService->hasJobCategoryEditAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    public function rules(): array
    {
        $categoryId = $this->input('id');

        return [
            'id' => 'required|integer|exists:job_categories,id',
            'name' => ['required', 'string', 'max:100', Rule::unique('job_categories', 'name')->ignore($categoryId)],
            'status' => 'nullable|integer|in:1,2',
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
            'id' => 'Category ID',
            'name' => 'Category name',
            'status' => 'Status',
        ];
    }

    protected $fields = ['id', 'name', 'status'];
}
