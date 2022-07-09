<?php

//var_dump(123123);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once dirname(__FILE__, 2) . '/Config/Database.php';
include_once dirname(__FILE__, 2) . '/Models/Employee.php';
include_once dirname(__FILE__, 2) . '/Repositories/EmployeeRepository.php';

use App\models\Employee;
use App\config\Database;
use App\Repositories\EmployeeRepository;

$database = new Database();
$conn = $database->getConnection();
$model = new Employee();
$repository = new EmployeeRepository($conn, $model);

$model->id = $params['id'];

$repository->getSingleEmployee();
if ($model->name != null) {
    // create array
    $emp_arr = array(
        "id" =>  $model->id,
        "name" => $model->name,
        "email" => $model->email,
        "age" => $model->age,
        "designation" => $model->designation,
        "created" => $model->created
    );

    http_response_code(200);
    echo json_encode($emp_arr);
} else {
    http_response_code(404);
    echo json_encode('Employee not found.');
}
