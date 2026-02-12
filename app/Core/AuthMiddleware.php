<?php
require_once __DIR__ . '/JWTHandler.php';

class AuthMiddleware {
    public static function handle()
    {
        // Get headers (getallheaders() may not exist, fallback to $_SERVER)
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $token = null;

        // Try custom header X-Auth-Token
        if (isset($headers['X-Auth-Token'])) {
            $token = $headers['X-Auth-Token'];
        } elseif (isset($_SERVER['HTTP_X_AUTH_TOKEN'])) {
            $token = $_SERVER['HTTP_X_AUTH_TOKEN'];
        }

        if (!$token) {
            Response::json(['error' => 'Authorization header missing'], 401);
            exit;
        }

        $jwt = new JWTHandler();
        $decoded = $jwt->validateToken($token);

        if (!$decoded) {
            Response::json(['error' => 'Invalid or expired token'], 401);
            exit;
        }

        // Return decoded user data
        return $decoded->data;
    }
}
