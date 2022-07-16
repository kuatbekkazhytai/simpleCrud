<?php

namespace App\Models;

class Employee extends Model
{
    /** @var string  */
    protected $dbTable = 'Employee';
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var $email */
    public $email;
    /** @var int */
    public $age;
    /** @var string */
    public $designation;
    /** @var string */
    public $created;

}
