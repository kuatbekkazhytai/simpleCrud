<?php

namespace App\Models;

include_once 'ModelInterface.php';

class Employee implements ModelInterface
{
    /** @var string  */
    private $db_table = 'Employee';
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

    /**
     * @return string
     */
    public function getTableName(): string {
        return $this->db_table;
    }
}
