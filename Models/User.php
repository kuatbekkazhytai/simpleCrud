<?php

namespace App\Models;

class User implements ModelInterface
{
    /** @var string  */
    private $dbTable = 'users';
    /** @var int */
    public $id;
    /** @var string */
    public $username;
    /** @var $email */
    public $email;
    /** @var int */
    private $password;
    /** @var string */
    public $createdAt;

    /**
     * @return string
     */
    public function getTableName(): string {
        return $this->dbTable;
    }
}
