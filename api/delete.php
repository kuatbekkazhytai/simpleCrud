<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once dirname(__FILE__, 2) . '/Config/Database.php';
include_once dirname(__FILE__, 2) . '/Models/Employee.php';
include_once dirname(__FILE__, 2) . '/Repositories/EmployeeRepository.php';

use App\Config\Database;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;


$database = new Database();
$conn = $database->getConnection();
$model = new Employee();
$repository = new EmployeeRepository($conn, $model);

$data = json_decode(file_get_contents("php://input"));

$model->id = $data->id;

if ($repository->deleteEmployee()) {
    echo json_encode("Employee deleted");
} else {
    echo json_encode("Data could not be deleted");
}
