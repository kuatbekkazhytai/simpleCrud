<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;

class BaseController
{
    /**
     * @var PDO|null
     */
    public $conn;

    /**
     *
     */
    public function __construct() {
        $this->conn = Database::getDbConnection();
    }
}