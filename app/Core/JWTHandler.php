<?php
use Firebase\JWT\JWT;
use Firebase\JWT\key;

class JWTHandler{
    private $secretKey;
    private $issuedAt;
    private $expire;
    
    public function __construct()
    {
        $this->secretKey = $_ENV['JWT_SECRET'];
        $this->issuedAt = time();
        $this->expire = $this->issuedAt + (60 * 60);
    }

    public function generateToken($user)
    {
        $payload = [
            'iat' => $this->issuedAt,
            'exp' => $this->expire,
            'data' => [
                'id' => $user['id'],
                'email' => $user['email'],
            ]
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');

    }

    public function validateToken($token)
    {
        try{
            return JWT::decode($token, new Key($this->secretKey, 'HS256'));
        }catch (Exception $e) {
            return false;
        }
    }
}