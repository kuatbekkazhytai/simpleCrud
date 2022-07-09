<?php

namespace App\Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once dirname(__FILE__, 2) . '/Config/Database.php';
include_once dirname(__FILE__, 2) . '/Models/Employee.php';
include_once dirname(__FILE__, 2) . '/Repositories/EmployeeRepository.php';

use App\Models\Employee;
use App\config\Database;
use App\Repositories\EmployeeRepository;

class EmployeeController
{
    private $repository;

    public function __construct()
    {
        $conn = Database::getDbConnection();
        $model = new Employee();
        $this->repository = new EmployeeRepository($conn, $model);
    }

    public function index() {
        $stmt = $this->repository->getEmployees();
        $itemCount = $stmt->rowCount();

        if ($itemCount > 0) {

            $employeeArr = array();
            $employeeArr['body'] = array();
            $employeeArr['itemCount'] = $itemCount;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $e = array(
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'age' => $age,
                    'designation' => $designation,
                    'created' => $created
                );
                array_push($employeeArr['body'], $e);
            }
            echo json_encode($employeeArr);
        } else {
            http_response_code(404);
            echo json_encode(
                array("message" => "No record found.")
            );
        }
    }
}