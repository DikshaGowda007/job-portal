<?php

namespace App\Modules\V1\RecruiterProfile\Services\Get;

use App\Constants\CommonConstant;
use App\Exceptions\UserNotFoundException;
use App\Repositories\V1\RecruiterProfileRepository;
use App\Traits\V1\AccessRightsTrait;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DetailsService
{
    use AccessRightsTrait;

    public function __construct(
        private RecruiterProfileRepository $recruiterProfileRepository,
    ) {
        $this->initializeUserAuthorizationData();
    }

    public function get(): JsonResponse
    {
        try {
            $profileDetails = $this->findProfile();
            $data = $this->formatResponse($profileDetails);

            return response()->json(CommonUtils::successDataResponse($data));
        } catch (UserNotFoundException $e) {
            return response()->json(CommonUtils::errorResponse($e->getMessage()));
        } catch (\Throwable $e) {
            CommonUtils::handleException($e->getMessage(), $e, CommonConstant::LOG_LEVEL_CRITICAL);

            return response()->json(CommonUtils::errorResponse('Failed to fetch recruiter profile'));
        }
    }

    private function findProfile(): Collection
    {
        $profileDetails = collect($this->recruiterProfileRepository->findByUserId($this->loggedInUserId)->first());

        if ($profileDetails->isEmpty()) {
            throw UserNotFoundException::withMessage('Recruiter profile not found');
        }

        return $profileDetails;
    }

    private function formatResponse(Collection $profileDetails): array
    {
        return [
            'id' => $profileDetails->get('id'),
            'user_id' => $profileDetails->get('user_id'),
            'company_name' => $profileDetails->get('company_name'),
            'company_logo_path' => $profileDetails->get('company_logo_path'),
            'company_about' => $profileDetails->get('company_about'),
            'company_website' => $profileDetails->get('company_website'),
            'company_size' => $profileDetails->get('company_size'),
            'industry' => $profileDetails->get('industry'),
            'company_phone' => $profileDetails->get('company_phone'),
            'city' => $profileDetails->get('city'),
            'state' => $profileDetails->get('state'),
            'country' => $profileDetails->get('country'),
            'linkedin_url' => $profileDetails->get('linkedin_url'),
        ];
    }
}
