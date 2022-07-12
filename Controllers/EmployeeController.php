<?php

namespace App\Controllers;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use App\Dto\EmployeeDto;

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
        echo $this->repository->getEmployeeById($request['id']);
    }

    /**
     * @param array|null $request
     * @return void
     */
    public function store(array $request): void {
        $dto = EmployeeDto::createFromRequest($request);

        if ($this->repository->createEmployee($dto)) {
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
        $dto = EmployeeDto::createFromRequest($request);

        if ($this->repository->updateEmployee($dto)) {
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
        if ($this->repository->deleteEmployeeById($request['post']->id)) {
            echo json_encode('Employee deleted');
        } else {
            echo json_encode('Data could not be deleted');
        }
    }
}
