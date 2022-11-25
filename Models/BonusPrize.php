<?php

namespace App\Models;

class BonusPrize implements PrizeInterface
{
    const MONEY_TO_BONUS_FACTOR = 2;

    private $minVal = 100;

    private $maxVal = 1000;

    public function getPrize() {
        echo 'bonus prize';
    }
}
