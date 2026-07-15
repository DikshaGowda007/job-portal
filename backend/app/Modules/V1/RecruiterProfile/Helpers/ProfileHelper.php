<?php

namespace App\Modules\V1\RecruiterProfile\Helpers;

use App\Http\Requests\V1\RecruiterProfile\Update\DetailsRequest;
use App\Modules\V1\RecruiterProfile\Bo\Update\DetailsBo;
use App\Repositories\DAO\V1\RecruiterProfileDAO;
use App\Traits\V1\AccessRightsTrait;

class ProfileHelper
{
    use AccessRightsTrait;

    public function __construct()
    {
        $this->initializeUserAuthorizationData();
    }

    public function prepareBo(DetailsRequest $detailsRequest): DetailsBo
    {
        $detailsBo = new DetailsBo;
        $detailsBo->setUserId($this->loggedInUserId);

        if ($detailsRequest->has('company_name')) {
            $detailsBo->setCompanyName($detailsRequest->input('company_name'));
        }
        if ($detailsRequest->has('company_about')) {
            $detailsBo->setCompanyAbout($detailsRequest->input('company_about'));
        }
        if ($detailsRequest->has('company_website')) {
            $detailsBo->setCompanyWebsite($detailsRequest->input('company_website'));
        }
        if ($detailsRequest->has('company_size')) {
            $detailsBo->setCompanySize($detailsRequest->input('company_size'));
        }
        if ($detailsRequest->has('industry')) {
            $detailsBo->setIndustry($detailsRequest->input('industry'));
        }
        if ($detailsRequest->has('company_phone')) {
            $detailsBo->setCompanyPhone($detailsRequest->input('company_phone'));
        }
        if ($detailsRequest->has('city')) {
            $detailsBo->setCity($detailsRequest->input('city'));
        }
        if ($detailsRequest->has('state')) {
            $detailsBo->setState($detailsRequest->input('state'));
        }
        if ($detailsRequest->has('country')) {
            $detailsBo->setCountry($detailsRequest->input('country'));
        }
        if ($detailsRequest->has('linkedin_url')) {
            $detailsBo->setLinkedinUrl($detailsRequest->input('linkedin_url'));
        }

        return $detailsBo;
    }

    public function prepareDao(DetailsBo $detailsBo): RecruiterProfileDAO
    {
        $recruiterProfileDAO = new RecruiterProfileDAO;

        if ($detailsBo->getCompanyName() !== null) {
            $recruiterProfileDAO->setCompanyName($detailsBo->getCompanyName());
        }
        if ($detailsBo->getCompanyAbout() !== null) {
            $recruiterProfileDAO->setCompanyAbout($detailsBo->getCompanyAbout());
        }
        if ($detailsBo->getCompanyWebsite() !== null) {
            $recruiterProfileDAO->setCompanyWebsite($detailsBo->getCompanyWebsite());
        }
        if ($detailsBo->getCompanySize() !== null) {
            $recruiterProfileDAO->setCompanySize($detailsBo->getCompanySize());
        }
        if ($detailsBo->getIndustry() !== null) {
            $recruiterProfileDAO->setIndustry($detailsBo->getIndustry());
        }
        if ($detailsBo->getCompanyPhone() !== null) {
            $recruiterProfileDAO->setCompanyPhone($detailsBo->getCompanyPhone());
        }
        if ($detailsBo->getCity() !== null) {
            $recruiterProfileDAO->setCity($detailsBo->getCity());
        }
        if ($detailsBo->getState() !== null) {
            $recruiterProfileDAO->setState($detailsBo->getState());
        }
        if ($detailsBo->getCountry() !== null) {
            $recruiterProfileDAO->setCountry($detailsBo->getCountry());
        }
        if ($detailsBo->getLinkedinUrl() !== null) {
            $recruiterProfileDAO->setLinkedinUrl($detailsBo->getLinkedinUrl());
        }

        return $recruiterProfileDAO;
    }

    public function prepareInitialDao(int $userId): RecruiterProfileDAO
    {
        $dao = new RecruiterProfileDAO;
        $dao->setUserId($userId);

        return $dao;
    }
}
