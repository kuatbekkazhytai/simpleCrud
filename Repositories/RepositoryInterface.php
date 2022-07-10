<?php

namespace App\Repositories;

use App\Models\ModelInterface;

interface RepositoryInterface
{
    /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface;
}
