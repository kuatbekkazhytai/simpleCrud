<?php

namespace App\Service;

use App\Models\PrizeInterface;

class PrizeDrawService
{
    const MONEY_PRIZE = 'MoneyPrize';
    const BONUS_PRIZE = 'BonusPrize';
    const OBJECT_PRIZE = 'ObjectPrize';
    const MONEY_PRIZE_ID = 1;
    const BONUS_PRIZE_ID = 2;
    const OBJECT_PRIZE_ID = 3;

    const PRIZES = [
        self::MONEY_PRIZE_ID => self::MONEY_PRIZE,
        self::BONUS_PRIZE_ID => self::BONUS_PRIZE,
        self::OBJECT_PRIZE_ID => self::OBJECT_PRIZE,
    ];

    /** @var int */
    public $prizeId;
    /** @var PrizeInterface */
    private $prizeModel;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->prizeId = rand(1, 3);
    }

    /**
     * @param PrizeInterface $model
     * @return $this
     */
    public function setPrizeModel(PrizeInterface $model): self
    {
        $this->prizeModel = $model;

        return $this;
    }

    /**
     * @return PrizeInterface
     */
    public function getPrizeType(): PrizeInterface
    {
        $namespace = '\\App\\Models';
        $className = self::PRIZES[$this->prizeId];
        $model = "$namespace\\$className";

        return new $model;
    }

    /**
     * @return void
     */
    public function drawPrize()
    {
        $this->prizeModel->getPrize();
    }
}
