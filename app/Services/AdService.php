<?php

namespace App\Services;

use App\Gateways\AdGateway;
use App\Models\Ad;

class AdService
{
    private $adGateway;

    public function __construct()
    {
        $this->adGateway = new AdGateway();
    }

    public function getRelevantAd(): Ad
    {
        $ad = $this->adGateway->getRelevant();
        $this->adGateway->decreaseLimit($ad->getId());

        return $ad;
    }
}