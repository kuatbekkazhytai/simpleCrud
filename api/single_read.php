<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once dirname(__FILE__, 2) . '/Config/Database.php';
include_once dirname(__FILE__, 2) . '/Models/Employee.php';
include_once dirname(__FILE__, 2) . '/Repositories/EmployeeRepository.php';

use App\models\Employee;
use App\config\Database;
use App\Repositories\EmployeeRepository;

$conn = Database::getDbConnection();
$model = new Employee();
$repository = new EmployeeRepository($conn, $model);

$model->id = $params['id'];


