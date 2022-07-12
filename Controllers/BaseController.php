<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;

class BaseController
{
    /** @var PDO|null */
    protected $conn;

    /**
     *
     */
    public function __construct() {
        $this->conn = Database::getDbConnection();
    }
}
