<?php

namespace App\Models;

class MoneyPrize implements PrizeInterface
{
    private $minVal = 100;

    private $maxVal = 1000;

    public function getPrize() {
        $moneyAmount = rand($this->minVal, $this->maxVal);
        echo $moneyAmount;
    }
}
