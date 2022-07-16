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

    public function __construct()
    {
        // set your default time-zone
        date_default_timezone_set('Asia/Kolkata');
        $this->issuedAt = time();

        // Token Validity (3600 second = 1hr)
        $this->expire = $this->issuedAt + 3600;

        // Set your secret or signature
        $this->jwt_secrect = 'this_is_my_secrect';
    }

    public function jwtEncodeData($iss, $data)
    {
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

    public function jwtDecodeData(string $jwt_token)
    {
        try {
            $decode = JWT::decode($jwt_token, new Key($this->jwt_secrect, 'HS256'));
            return $decode->data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
