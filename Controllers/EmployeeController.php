<?php

namespace App\Controllers;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;

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
     */
    public function store(array $request = null): void {
        $requestData = $request['post'];
        $this->model->name = $requestData->name;
        $this->model->email = $requestData->email;
        $this->model->age = $requestData->age;
        $this->model->designation = $requestData->designation;
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
    public function update(array $request): void {
        $requestData = $request['post'];
        $this->model->id = $requestData->id;
        $this->model->name = $requestData->name;
        $this->model->email = $requestData->email;
        $this->model->age = $requestData->age;
        $this->model->designation = $requestData->designation;
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
    public function delete(array $request): void {
        $requestData = $request['post'];
        $this->model->id = $requestData->id;

        if ($this->repository->deleteEmployee()) {
            echo json_encode('Employee deleted');
        } else {
            echo json_encode('Data could not be deleted');
        }
    }
}
