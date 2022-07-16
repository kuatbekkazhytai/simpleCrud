<?php

namespace App\Dto;

class EmployeeDto implements DtoInterface
{
    public $id;
    public $name;
    public $email;
    public $age;
    public $designation;
    public $created;

    /**
     * @param array $request
     * @return EmployeeDto
     */
    public static function createFromRequest(array $request): EmployeeDto {
        $requestData = $request['post'];
        $dto = new self();
        $dto->id = $requestData->id ?? null;
        $dto->name = $requestData->name;
        $dto->email = $requestData->email;
        $dto->age = $requestData->age;
        $dto->designation = $requestData->designation;
        $dto->created = date('Y-m-d H:i:s');

        return $dto;
    }
}
