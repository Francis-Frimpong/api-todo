<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../Core/JWTHandler.php';


class AuthController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register()
    {
         // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Check if JSON is valid
        if (!$data) {
            Response::json(['error' => 'Invalid JSON or empty body'], 400);
            return;
        }

        // Check required fields
        if (
        empty(trim($data['name'])) ||
        empty(trim($data['email'])) ||
        empty(trim($data['password']))
    ) {
        Response::json(['error' => 'All fields are required'], 400);
        return;
    }

        // Check if email already exists
        $existingUser = $this->userModel->findUserByEmail($data['email']);
        if($existingUser){
              Response::json(['error' => 'Email already registered'], 400);
            return;
        }

        // Hash password
        $hashPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Create user
        $created = $this->userModel->createUser($data['name'], $data['email'], $hashPassword);

        if($created){
            Response::json([
            'status' => 'success',
            'message' => 'User registered successfully'
        ], 201);
        } else {
            Response::json([
                'status' => 'error',
                'message' => 'Registration failed'
            ], 500);
        }
    }

    public function login()
    {
        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);
        
        if(!$data){
            Response::json(['error' => 'Invalid JSON or empty body'], 400);
            return;
        }

        // Validate required fields
        if(empty(trim($data['email'])) || empty(trim($data['password']))){
            Response::json(['error' => 'Email and password are required'], 400);
            return;
        }

        // Find user by email
        $user = $this->userModel->findUserByEmail($data['email']);

        if(!$user){
            Response::json(['error' => 'Invalid credentials'], 401);
        }

        // Verify password
        if(!password_verify($data['password'], $user['password'])){
            Response::json(['error' => 'invalid credentials'], 401);
            return;
        }

        // Login successful (JWT will be added here later)
        $jwt = new JWTHandler();
        $token = $jwt->generateToken($user);

        Response::json([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $token
        ]);
    }

}
