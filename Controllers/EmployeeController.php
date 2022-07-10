<?php

namespace App\Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once dirname(__FILE__, 2) . '/Config/Database.php';
include_once dirname(__FILE__, 2) . '/Models/Employee.php';
include_once dirname(__FILE__, 2) . '/Repositories/EmployeeRepository.php';
include_once dirname(__FILE__) . '/BaseController.php';

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use PDO;

class EmployeeController extends BaseController
{
    /** @var EmployeeRepository  */
    private $repository;
    /** @var Employee  */
    private $model;

    /**
     *
     */
    public function __construct() {
        parent::__construct();
        $this->model = new Employee();
        $this->repository = new EmployeeRepository($this->conn, $this->model);
    }

    /**
     * @return void
     */
    public function index() {
        $stmt = $this->repository->getEmployees();
        $itemCount = $stmt->rowCount();
        if ($itemCount > 0) {
            $employees['itemCount'] = $itemCount;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $employee = array(
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'age' => $age,
                    'designation' => $designation,
                    'created' => $created
                );
                $employees['body'][] = $employee;
            }
            echo json_encode($employees);
        } else {
            http_response_code(404);
            echo json_encode(
                array('message' => 'No record found.')
            );
        }
    }

    /**
     * @param array $request
     * @return void
     */
    public function show(array $request): void {
        $this->model->id = $request['id'];
        $this->repository->getSingleEmployee();
        if ($this->model->name != null) {
            $employee = array(
                'id' =>  $this->model->id,
                'name' => $this->model->name,
                'email' => $this->model->email,
                'age' => $this->model->age,
                'designation' => $this->model->designation,
                'created' => $this->model->created
            );
            http_response_code(200);
            echo json_encode($employee);
        } else {
            http_response_code(404);
            echo json_encode('Employee not found.');
        }
    }

    public function store(array $request = null) {
        $data = json_decode(file_get_contents('php://input'));
        $this->model->name = $data->name;
        $this->model->email = $data->email;
        $this->model->age = $data->age;
        $this->model->designation = $data->designation;
        $this->model->created = date('Y-m-d H:i:s');

        if ($this->repository->createEmployee()) {
            echo 'Employee created successfully';
        } else {
            echo 'Employee could not be created';
        }
    }

    /**
     * @param array|null $request
     * @return void
     */
    public function update(array $request = null): void {
        $data = json_decode(file_get_contents('php://input'));
        $this->model->id = $data->id;
        $this->model->name = $data->name;
        $this->model->email = $data->email;
        $this->model->age = $data->age;
        $this->model->designation = $data->designation;
        $this->model->created = date('Y-m-d H:i:s');

        if ($this->repository->updateEmployee()) {
            echo json_encode('Employee data updated.');
        } else {
            echo json_encode('Data could not be updated');
        }
    }

    /**
     * @param array|null $request
     * @return void
     */
    public function delete(array $request = null): void {
        $data = json_decode(file_get_contents('php://input'));
        $this->model->id = $data->id;

        if ($this->repository->deleteEmployee()) {
            echo json_encode('Employee deleted');
        } else {
            echo json_encode('Data could not be deleted');
        }
    }
}
