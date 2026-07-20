<?php

namespace App\Http\Requests\V1\JobApplication\UpdateStatus;

use App\Constants\JobApplicationConstants;
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
        if ($this->accessService->hasApplicationUpdateStatusAccess() === false) {
            throw AccessForbiddenException::withMessage();
        }
    }

    public function rules(): array
    {
        return [
            'application_id' => 'required|integer|exists:job_applications,id',
            'status' => 'required|in:'.implode(',', JobApplicationConstants::RECRUITER_ALLOWED_STATUSES),
            'recruiter_notes' => 'nullable|string|max:2000',
            'interview_scheduled_at' => ['nullable', 'date', 'after:now', 'required_if:status,'.JobApplicationConstants::STATUS_INTERVIEW],
            'interview_location' => 'nullable|string|max:500',
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
            'application_id' => 'Application',
            'status' => 'Status',
            'recruiter_notes' => 'Recruiter Notes',
            'interview_scheduled_at' => 'Interview Date & Time',
            'interview_location' => 'Interview Location',
        ];
    }
}
