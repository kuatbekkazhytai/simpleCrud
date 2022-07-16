<?php

namespace App\Models;

class User extends Model
{
    /** @var string  */
    protected $dbTable = 'users';
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
}
