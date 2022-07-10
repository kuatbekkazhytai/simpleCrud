<?php

namespace App\Repositories;

use App\Models\ModelInterface;
use PDO;

class BaseRepository implements RepositoryInterface
{
    /** @var ModelInterface */
    protected $model;
    /** @var PDO */
    protected $conn;

    /**
     * @param PDO $conn
     * @param ModelInterface $model
     */
    public function __construct(PDO $conn, ModelInterface $model) {
        $this->model = $model;
        $this->conn = $conn;
    }

    /**
     * @return ModelInterface
     */
    public function getModel(): ModelInterface {
        return $this->model;
    }
}
