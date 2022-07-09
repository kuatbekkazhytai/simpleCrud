<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once dirname(__FILE__, 2) . '/Config/Database.php';
include_once dirname(__FILE__, 2) . '/Models/Employee.php';
include_once dirname(__FILE__, 2) . '/Repositories/EmployeeRepository.php';

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use App\config\Database;

$conn = Database::getDbConnection();
$model = new Employee();
$repository = new EmployeeRepository($conn, $model);

$data = json_decode(file_get_contents("php://input"));
$model->name = $data->name;
$model->email = $data->email;
$model->age = $data->age;
$model->designation = $data->designation;
$model->created = date('Y-m-d H:i:s');
$repository = new EmployeeRepository($conn, $model);

if ($repository->createEmployee()) {
    echo 'Employee created successfully';
} else {
    echo 'Employee could not be created';
}
