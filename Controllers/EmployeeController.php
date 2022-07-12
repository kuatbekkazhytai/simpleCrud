<?php

namespace App\Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

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
        echo $this->repository->getEmployees();
    }

    /**
     * @param array $request
     * @return void
     */
    public function show(array $request): void {
        $this->model->id = $request['id'];
        echo $this->repository->getSingleEmployee();
    }

    /**
     * @param array|null $request
     * @return void
     * TODO remove getting post data directly from php://input. Use request
     */
    public function store(array $request = null): void {
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
     * TODO remove getting post data directly from php://input. Use request
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
     * TODO remove getting post data directly from php://input. Use request
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
