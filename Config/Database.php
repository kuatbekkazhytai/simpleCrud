<?php

namespace App\Config;

use PDO;
use Exception;

class Database {
    /** @var Database */
    private static $instance;
    /** @var PDO */
    private $dbConn;

    /**
     * private constructor to avoid initialization
     */
    private function __construct() {}

    /**
     * @return Database
     */
    private static function getInstance(): Database {
        if (self::$instance == null) {
            $className = __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }

    /**
     * @return Database
     */
    private static function initConnection(): Database {
        $db = self::getInstance();
        $configs = DbConfigs::getConfigs();
        $db->dbConn = new PDO('mysql:host=' . $configs['db_host'] . ';dbname=' . $configs['db_name'], $configs['db_username'], $configs['db_password']);
        $db->dbConn->exec('set names utf8');

        return $db;
    }

    /**
     * @return PDO|null
     */
    public static function getDbConnection(): ?PDO {
        try {
            $db = self::initConnection();
            return $db->dbConn;
        } catch (Exception $ex) {
            echo 'I was unable to open a connection to the database. ' . $ex->getMessage();
            return null;
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function __clone() {
        throw new Exception("Can't clone a singleton");
    }
}
