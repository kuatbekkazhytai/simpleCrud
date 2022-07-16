<?php
namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtHandler
{
    protected $jwt_secrect;
    protected $token;
    protected $issuedAt;
    protected $expire;
    protected $jwt;

    public function __construct() {
        date_default_timezone_set('Asia/Kolkata');
        $this->issuedAt = time();
        $this->expire = $this->issuedAt + 3600;
        $this->jwt_secrect = 'this_is_my_secrect';
    }

    /**
     * @param $iss
     * @param $data
     * @return string
     */
    public function jwtEncodeData($iss, $data) {
        $iss = $_SERVER["HTTP_HOST"];
        $iss = "THE_ISSUER";

        $this->token = array(
            "iss" => "THE_ISSUER",
            "aud" => "THE_AUDIENCE",
            "iat" => $this->issuedAt,
            "exp" => $this->expire,
            "data" => $data
        );

        $this->jwt = JWT::encode($this->token, $this->jwt_secrect, 'HS256');
        return $this->jwt;
    }

    /**
     * @param string $jwt_token
     * @return mixed
     */
    public function jwtDecodeData(string $jwt_token) {
        try {
            $decode = JWT::decode($jwt_token, new Key($this->jwt_secrect, 'HS256'));
            return $decode->data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
