<?php

namespace App\Controllers;

use App\Service\PrizeDrawService;

class PrizeController
{
    private $service;

    public function __construct()
    {
        $this->service = new PrizeDrawService();
    }

    public function getPrize()
    {
        $prizeModel = $this->service->getPrizeType();
        $this->service->setPrizeModel($prizeModel);
        $this->service->drawPrize();
    }
}
