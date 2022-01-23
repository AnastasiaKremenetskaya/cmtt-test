<?php

namespace App\Http\Controllers;

use App\Gateways\AdGateway;
use App\Http\Requests\AdRequest;
use App\Http\Responses\AdResponse;
use App\Services\AdService;

class AdController
{
    private $adRequest;
    private $adResponse;

    public function __construct()
    {
        $this->adRequest = new AdRequest();
        $this->adResponse = new AdResponse();
    }

    /**
     * Открутка объявления
     *
     * @return string|null
     */
    public function relevant(): ?string
    {
        $ad = (new AdService())->getRelevantAd();

        return $this->adResponse->getSuccessResponse($ad);
    }

    /**
     * Создание объявления
     *
     * @return string|null
     */
    public function store(): ?string
    {
        $validation = $this->adRequest->validate(input()->all());

        if ($validation->fails()) {
            return $this->adResponse->getErrorResponse($validation->errors()->all());
        } else {
            $validData = $validation->getValidData();
            $ad = (new AdGateway())->create($validData);

            return $this->adResponse->getSuccessResponse($ad);
        }
    }

    /**
     * Редактирование объявления
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
            $validData = $validation->getValidData();
            $ad = (new AdGateway())->update($id, $validData);

            return $this->adResponse->getSuccessResponse($ad);
        }
    }
}
