<?php

namespace App\Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

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
