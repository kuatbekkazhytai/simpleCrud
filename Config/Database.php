<?php

namespace App\Config;

use PDO;
use PDOException;
// TODO refactor to singleton
class Database {

    private $host = "127.0.0.1";
    private $database_name = "phpapidb";
    private $username = "kuatbek";
    private $password = "lfc8milan22";
    public $conn;

    /**
     * @return PDO|null
     */
    public function getConnection(): PDO {
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception){
            echo "Database could not be connected: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
