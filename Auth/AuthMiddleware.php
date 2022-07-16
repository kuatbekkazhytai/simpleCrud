<?php

namespace App\Auth;

use App\Repositories\RepositoryInterface;

class AuthMiddleware extends JwtHandler
{
    protected $headers;
    protected $token;
    /** @var RepositoryInterface */
    private $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository) {
        parent::__construct();
        $this->repository = $repository;
        $this->headers = getallheaders();
    }

    /**
     * @return array
     */
    public function isValid(): array {
        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {
            $data = $this->jwtDecodeData($matches[1]);
            if (
                isset($data->user_id) &&
                $user = $this->repository->getUserById($data->user_id)
            ) {
                return [
                    'success' => 1,
                    'user' => $user
                ];
            } else {
                return [
                    'success' => 0,
                    'message' => 'could not decode token',
                ];
            }
        } else {
            return [
                'success' => 0,
                'message' => 'Token not found in request'
            ];
        }
    }
}
