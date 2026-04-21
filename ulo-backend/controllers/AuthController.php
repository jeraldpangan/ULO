<?php
/**
 * Auth Controller
 * Handles authentication endpoints
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/JWT.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';

class AuthController {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Register a new user
     * POST /api/auth/register
     */
    public function register() {
        try {
            $input = Validator::getJsonBody();
            
            // Validation
            Validator::startValidation();
            Validator::required($input['full_name'] ?? '', 'Full Name');
            Validator::required($input['email'] ?? '', 'Email');
            Validator::email($input['email'] ?? '', 'Email');
            Validator::required($input['password'] ?? '', 'Password');
            Validator::password($input['password'] ?? '', 'Password');
            Validator::required($input['confirm_password'] ?? '', 'Confirm Password');
            
            if (!Validator::passed()) {
                Response::validationError(Validator::getErrors());
            }
            
            // Check passwords match
            if ($input['password'] !== $input['confirm_password']) {
                Response::error('Passwords do not match', 400);
            }
            
            // Check if email already exists
            $existingUser = $this->userModel->findByEmail($input['email']);
            if ($existingUser) {
                Response::error('Email already registered', 400);
            }
            
            // Create user
            $userData = [
                'full_name' => Validator::sanitize($input['full_name']),
                'email' => Validator::sanitize($input['email']),
                'password' => $input['password'],
                'phone' => $input['phone'] ?? null,
                'address' => $input['address'] ?? null,
                'role' => 'student'
            ];
            
            $userId = $this->userModel->create($userData);
            
            if ($userId) {
                // Generate JWT token
                $token = JWT::encode([
                    'id' => $userId,
                    'email' => $input['email'],
                    'role' => 'student'
                ]);
                
                Response::success([
                    'user_id' => $userId,
                    'email' => $input['email'],
                    'token' => $token
                ], 'User registered successfully', 201);
            } else {
                Response::error('Failed to create user', 500);
            }
        } catch (Exception $e) {
            Response::error('Registration error: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Login user
     * POST /api/auth/login
     */
    public function login() {
        try {
            $input = Validator::getJsonBody();
            
            // Validation
            Validator::startValidation();
            Validator::required($input['email'] ?? '', 'Email');
            Validator::email($input['email'] ?? '', 'Email');
            Validator::required($input['password'] ?? '', 'Password');
            
            if (!Validator::passed()) {
                Response::validationError(Validator::getErrors());
            }
            
            // Find user
            $user = $this->userModel->findByEmail($input['email']);
            
            if (!$user) {
                Response::unauthorized('Invalid email or password');
            }
            
            // Verify password
            if (!$this->userModel->verifyPassword($input['password'], $user['password'])) {
                Response::unauthorized('Invalid email or password');
            }
            
            // Check if user is active
            if ($user['status'] !== 'active') {
                Response::forbidden('User account is inactive');
            }
            
            // Generate JWT token
            $token = JWT::encode([
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);
            
            // Log login
            $this->logLogin($user['id'], 'successful');
            
            Response::success([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'token' => $token
            ], 'Login successful');
            
        } catch (Exception $e) {
            Response::error('Login error: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Log login attempt
     */
    private function logLogin($userId, $type) {
        try {
            $connection = new Connection();
            $db = $connection->connect();
            
            $query = "INSERT INTO login_logs (user_id, ip_address, user_agent, login_type) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query); /** @var \PDOStatement $stmt */
            
            $stmt->execute([
                $userId,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                $type
            ]);
        } catch (Exception $e) {
            // Silently fail on logging
        }
    }
}
