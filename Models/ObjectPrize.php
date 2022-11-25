<?php

namespace App\Models;

class ObjectPrize implements PrizeInterface
{
    public function getPrize() {
        echo 'object prize';
    }
}
