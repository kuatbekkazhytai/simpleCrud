<?php
namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtHandler
{
    /** @var string */
    protected $jwt_secret;
    /** @var  */
    protected $token;
    /** @var int */
    protected $issuedAt;
    /** @var int */
    protected $expire;
    /** @var  */
    protected $jwt;

    /**
     *
     */
    public function __construct() {
        date_default_timezone_set('Asia/Kolkata');
        $this->issuedAt = time();
        $this->expire = $this->issuedAt + 3600;
        $this->jwt_secret = 'this_is_my_secret';
    }

    /**
     * @param array $data
     * @return string
     */
    public function jwtEncodeData(array $data): string {
        $iss = $_SERVER['HTTP_HOST'];

        $this->token = array(
            "iss" => $iss,
            "aud" => $iss,
            "iat" => $this->issuedAt,
            "exp" => $this->expire,
            "data" => $data
        );

        $this->jwt = JWT::encode($this->token, $this->jwt_secret, 'HS256');

        return $this->jwt;
    }

    /**
     * @param string $jwt_token
     * @return mixed
     */
    public function jwtDecodeData(string $jwt_token) {
        try {
            $decode = JWT::decode($jwt_token, new Key($this->jwt_secret, 'HS256'));
            return $decode->data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
