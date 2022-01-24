<?php

namespace App\Services;

use App\Exceptions\Gateway\RecordNotFoundException;
use App\Exceptions\InternalServerException;
use App\Gateways\AdGateway;
use App\Models\Ad;

class AdService
{
    /**
     * @var AdGateway
     */
    private $adGateway;

    public function __construct()
    {
        $this->adGateway = new AdGateway();
    }

    /**
     * Get most relevant ad
     *
     * @return Ad
     * @throws InternalServerException|RecordNotFoundException
     */
    public function getRelevantAd(): Ad
    {
        $ad = $this->adGateway->getRelevant();
        $this->adGateway->decreaseLimit($ad->getId());

        return $ad;
    }
}