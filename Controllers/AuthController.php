<?php

namespace App\Controllers;

use App\Auth\AuthMiddleware;
use App\Helpers\Response;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Dto\UserDto;
use PDOException;
use App\Auth\JwtHandler;

class AuthController extends BaseController
{
    /** @var User  */
    private $model;
    /** @var UserRepository */
    private $repository;

    /**
     *
     */
    public function __construct() {
        parent::__construct();
        $this->model = new User();
        $this->repository = new UserRepository($this->conn, $this->model);
    }

    /**
     * @param array $request
     * @return void
     */
    public function register(array $request) {
        $dto = UserDto::createFromRequest($request);
        $response = new Response(422);
        if (
            !isset($dto->username)
            || !isset($dto->email)
            || !isset($dto->password)
            || empty(trim($dto->username))
            || empty(trim($dto->email))
            || empty(trim($dto->password))
        ) {
            $fields = ['fields' => ['username', 'email', 'password']];
            $response->setMessage('Please Fill in all Required Fields!')
                ->setExtra($fields);
        } else {
            $dto->username = trim($dto->username);
            $dto->email = trim($dto->email);
            $dto->password = trim($dto->password);
            if (!filter_var($dto->email, FILTER_VALIDATE_EMAIL)) {
                $response->setMessage('Invalid Email Address!');
            } elseif (strlen($dto->password) < 8) {
                $response->setMessage('Your password must be at least 8 characters long!');
            } elseif (strlen($dto->username) < 3) {
                $response->setMessage('Your name must be at least 3 characters long!');
            } else {
                try {
                    if ($user = $this->repository->getUserByEmail($dto->email))  {
                        $response->setMessage('This E-mail already in use!');
                    } else {
                        if ($user = $this->repository->createUser($dto)) {
                            $response->setStatus(201)->setMessage('You have successfully registered.');
                        }
                    }
                } catch (PDOException $e) {
                    $response->setStatus(500)->setMessage($e->getMessage());
                }
            }
        }
        echo json_encode($response->message());
    }

    /**
     * @param array $request
     * @return void
     */
    public function login(array $request) {
        $dto = UserDto::createFromRequest($request);
        $response = new Response(422);
        if (!isset($dto->email)
            || !isset($dto->password)
            || empty(trim($dto->email))
            || empty(trim($dto->password))
        ) {
            $fields = ['fields' => ['email','password']];
            $response->setMessage('Please Fill in all Required Fields!')->setExtra($fields);
        } else {
            $dto->email = trim($dto->email);
            $dto->password = trim($dto->password);

            if (!filter_var($dto->email, FILTER_VALIDATE_EMAIL)) {
                $response->setMessage('Invalid Email Address!');
            } elseif (strlen($dto->password) < 8) {
                $response->setMessage('Your password must be at least 8 characters long!');
            } else {
                try {
                    if ($user = $this->repository->getUserByEmail($dto->email)) {

                        $check_password = password_verify($dto->password, $user['password']);
                        if ($check_password) {
                            $jwt = new JwtHandler();
                            $token = $jwt->jwtEncodeData(
                                'http://localhost/php_auth_api/',
                                array("user_id" => $user['id'])
                            );
                            $response->setStatus(200)
                                ->setMessage('You have successfully logged in.')
                                ->setExtra(['token' => $token]);
                        } else {
                            $response->setMessage('Invalid Password');
                        }
                    }  else {
                        $response->setMessage('Invalid Email Address!');
                    }
                } catch (PDOException $e) {
                    $response->setStatus(500)->setMessage( $e->getMessage());
                }
            }
        }
        echo json_encode($response->message());
    }

    /**
     * @return void
     */
    public function getUser() {
        $allHeaders = getallheaders();
        $auth = new AuthMiddleware($this->repository, $allHeaders);

        echo json_encode($auth->isValid());
    }
}
