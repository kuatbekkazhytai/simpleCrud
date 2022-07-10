<?php

namespace App\Config;

class DbConfigs
{
    /** @var string */
    private static $host = '127.0.0.1';
    /** @var string */
    private static $database_name = 'phpapidb';
    /** @var string */
    private static $username = 'kuatbek';
    /** @var string */
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
