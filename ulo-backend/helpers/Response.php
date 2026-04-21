<?php
/**
 * Response Helper Class
 * Handles standardized JSON responses with proper HTTP status codes
 */

class Response {
    
    /**
     * Send successful response
     * 
     * @param mixed $data Response data
     * @param string $message Success message
     * @param int $statusCode HTTP status code (default 200)
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Send error response
     * 
     * @param string $message Error message
     * @param int $statusCode HTTP status code (default 400)
     * @param array $errors Additional error details
     */
    public static function error($message = 'Error', $statusCode = 400, $errors = null) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ];
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Send validation error response
     * 
     * @param array $validationErrors Validation error details
     */
    public static function validationError($validationErrors) {
        self::error('Validation failed', 422, $validationErrors);
    }
    
    /**
     * Send unauthorized response
     * 
     * @param string $message Error message
     */
    public static function unauthorized($message = 'Unauthorized') {
        self::error($message, 401);
    }
    
    /**
     * Send forbidden response
     * 
     * @param string $message Error message
     */
    public static function forbidden($message = 'Forbidden') {
        self::error($message, 403);
    }
    
    /**
     * Send not found response
     * 
     * @param string $message Error message
     */
    public static function notFound($message = 'Not found') {
        self::error($message, 404);
    }
    
    /**
     * Send server error response
     * 
     * @param string $message Error message
     */
    public static function serverError($message = 'Internal server error') {
        self::error($message, 500);
    }
}
