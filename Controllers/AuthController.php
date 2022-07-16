<?php

namespace App\Controllers;

use App\Auth\AuthMiddleware;
use App\Dto\EmployeeDto;
use App\Dto\UserDto;
use App\Models\User;
use App\Repositories\UserRepository;
use PDO;
use PDOException;
use App\Auth\JwtHandler;

class AuthController extends BaseController
{
    /** @var User  */
    private $model;
    /** @var UserRepository */
    private $repository;

    public function __construct() {
        parent::__construct();
        $this->model = new User();
        $this->repository = new UserRepository($this->conn, $this->model);
    }

    public function register(array $request) {
        $dto = UserDto::createFromRequest($request);

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $returnData = $this->msg(0, 404, 'Page Not Found!');
        } elseif  (
            !isset($dto->username)
            || !isset($dto->email)
            || !isset($dto->password)
            || empty(trim($dto->username))
            || empty(trim($dto->email))
            || empty(trim($dto->password))
        ) {
            $fields = ['fields' => ['username', 'email', 'password']];
            $returnData = $this->msg(0, 422, 'Please Fill in all Required Fields!', $fields);
        } else {
            $dto->username = trim($dto->username);
            $dto->email = trim($dto->email);
            $dto->password = trim($dto->password);
            if (!filter_var($dto->email, FILTER_VALIDATE_EMAIL)) {
                $returnData = $this->msg(0, 422, 'Invalid Email Address!');
            } elseif (strlen($dto->password) < 8) {
                $returnData = $this->msg(0, 422, 'Your password must be at least 8 characters long!');
            } elseif (strlen($dto->username) < 3) {
                $returnData = $this->msg(0, 422, 'Your name must be at least 3 characters long!');
            } else {
                try {
                    if ($user = $this->repository->getUserByEmail($dto->email))  {
                        $returnData = $this->msg(0, 422, 'This E-mail already in use!');
                    } else {
                        if ($user = $this->repository->createUser($dto)) {
                            $returnData = $this->msg(1, 201, 'You have successfully registered.');
                        }
                    }
                } catch (PDOException $e) {
                    $returnData = $this->msg(0, 500, $e->getMessage());
                }
            }
        }
        echo json_encode($returnData);
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'));

        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            $returnData = $this->msg(0,404,'Page Not Found!');
        } elseif(!isset($data->email)
            || !isset($data->password)
            || empty(trim($data->email))
            || empty(trim($data->password))
        ) {
            $fields = ['fields' => ['email','password']];
            $returnData = $this->msg(0,422,'Please Fill in all Required Fields!',$fields);
        } else {
            $email = trim($data->email);
            $password = trim($data->password);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $returnData = $this->msg(0, 422, 'Invalid Email Address!');
            } elseif (strlen($password) < 8) {
                $returnData = $this->msg(0, 422, 'Your password must be at least 8 characters long!');
            } else {
                try {

                    $fetch_user_by_email = "SELECT * FROM `users` WHERE `email`=:email";
                    $query_stmt = $this->conn->prepare($fetch_user_by_email);
                    $query_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                    $query_stmt->execute();

                    // IF THE USER IS FOUNDED BY EMAIL
                    if ($query_stmt->rowCount()) {
                        $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                        $check_password = password_verify($password, $row['password']);

                        // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
                        // IF PASSWORD IS CORRECT THEN SEND THE LOGIN TOKEN
                        if ($check_password) {
                            $jwt = new JwtHandler();
                            $token = $jwt->jwtEncodeData(
                                'http://localhost/php_auth_api/',
                                array("user_id" => $row['id'])
                            );

                            $returnData = [
                                'success' => 1,
                                'message' => 'You have successfully logged in.',
                                'token' => $token
                            ];
                        } else {
                            $returnData = $this->msg(0, 422, 'Invalid Password!');
                        }
                    } else {
                        $returnData = $this->msg(0, 422, 'Invalid Email Address!');
                    }
                } catch (PDOException $e) {
                    $returnData = $this->msg(0, 500, $e->getMessage());
                }
            }
        }
        echo json_encode($returnData);
    }

    public function getUser() {
        $allHeaders = getallheaders();
        $auth = new AuthMiddleware($this->repository, $allHeaders);

        echo json_encode($auth->isValid());
    }

    protected function msg($success, $status, $message, $extra = []) {
        return array_merge([
            'success' => $success,
            'status' => $status,
            'message' => $message
        ], $extra);
    }
}
