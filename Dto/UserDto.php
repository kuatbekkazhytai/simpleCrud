<?php

namespace App\Dto;

class UserDto implements DtoInterface
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $createdAt;

    /**
     * @param array $request
     * @return UserDto
     */
    public static function createFromRequest(array $request): UserDto {
        $requestData = $request['post'];
        $dto = new self();
        $dto->id = $requestData->id ?? null;
        $dto->username = $requestData->username ?? null;
        $dto->email = $requestData->email;
        $dto->password = $requestData->password;
        $dto->createdAt = date('Y-m-d H:i:s');

        return $dto;
    }
}
