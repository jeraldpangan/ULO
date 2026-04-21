<?php
/**
 * Auth Middleware
 * Verifies JWT token and authenticated user
 */

require_once __DIR__ . '/../helpers/JWT.php';
require_once __DIR__ . '/../helpers/Response.php';

class AuthMiddleware {
    
    /**
     * Verify JWT token from Authorization header
     * 
     * @return array Decoded token payload
     */
    public static function verify() {
        // Check Authorization header
        $headers = getallheaders();
        
        if (!isset($headers['Authorization'])) {
            Response::unauthorized('Authorization header missing');
        }
        
        $authHeader = $headers['Authorization'];
        
        // Extract token from "Bearer <token>"
        if (!preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
            Response::unauthorized('Invalid authorization header format');
        }
        
        $token = $matches[1];
        
        try {
            $payload = JWT::decode($token);
            return $payload;
        } catch (Exception $e) {
            Response::unauthorized('Invalid or expired token: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if user has admin role
     * 
     * @param array $user User payload from token
     */
    public static function isAdmin($user) {
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            Response::forbidden('Admin access required');
        }
    }
}
