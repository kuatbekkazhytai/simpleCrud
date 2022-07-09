<?php

namespace App\Models;

interface ModelInterface
{
    /**
     * @return string
     */
    public function getTableName(): string;
}