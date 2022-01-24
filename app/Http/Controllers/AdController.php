<?php

namespace App\Http\Controllers;

use App\Gateways\AdGateway;
use App\Http\Requests\AdRequest;
use App\Http\Responses\AdResponse;
use App\Services\AdService;
use Exception;

class AdController
{
    /**
     * @var AdRequest
     */
    private $adRequest;

    /**
     * @var AdResponse
     */
    private $adResponse;

    public function __construct()
    {
        $this->adRequest = new AdRequest();
        $this->adResponse = new AdResponse();
    }

    /**
     * Show most relevant ad
     *
     * @return string|null
     */
    public function relevant(): ?string
    {
        try {
            $ad = (new AdService())->getRelevantAd();
            return $this->adResponse->getSuccessResponse($ad);
        } catch (Exception $e) {
            return $this->adResponse->getErrorResponse([$e->getMessage()]);
        }
    }

    /**
     * Create ad
     *
     * @return string|null
     */
    public function store(): ?string
    {
        $validation = $this->adRequest->validate(input()->all());

        if ($validation->fails()) {
            return $this->adResponse->getErrorResponse($validation->errors()->all());
        } else {
            try {
                $validData = $validation->getValidData();
                $ad = (new AdGateway())->create($validData);

                return $this->adResponse->getSuccessResponse($ad);
            } catch (Exception $e) {
                return $this->adResponse->getErrorResponse([$e->getMessage()]);
            }
        }
    }

    /**
     * Edit ad
     *
     * @param mixed $id
     * @return string|null
     */
    public function update($id): ?string
    {
        parse_str(file_get_contents('php://input'), $_PUT);

        $validation = $this->adRequest->validate($_PUT);

        if ($validation->fails()) {
            return $this->adResponse->getErrorResponse($validation->errors()->all());
        } else {
            try {
                $validData = $validation->getValidData();
                $ad = (new AdGateway())->update($id, $validData);

                return $this->adResponse->getSuccessResponse($ad);
            } catch (Exception $e) {
                return $this->adResponse->getErrorResponse([$e->getMessage()]);
            }
        }
    }
}
