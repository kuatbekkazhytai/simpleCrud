<?php

namespace App\Config;

class DbConfigs
{
    private static $host = '127.0.0.1';
    private static $database_name = 'phpapidb';
    private static $username = 'kuatbek';
    private static $password = 'lfc8milan22';

    /**
     * @return array
     */
    public static function getConfigs(): array {
        return array(
            'db_host' => self::$host,
            'db_name' => self::$database_name,
            'db_username' => self::$username,
            'db_password' => self::$password,

        );
    }
}