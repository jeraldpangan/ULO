<?php
/**
 * Validator Helper Class
 * Handles input validation
 */

class Validator {
    
    private static $errors = [];
    
    /**
     * Start validation process
     */
    public static function startValidation() {
        self::$errors = [];
    }
    
    /**
     * Get validation errors
     */
    public static function getErrors() {
        return self::$errors;
    }
    
    /**
     * Check if validation passed
     */
    public static function passed() {
        return empty(self::$errors);
    }
    
    /**
     * Validate email
     */
    public static function email($value, $fieldName = 'Email') {
        if (empty($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            self::$errors[$fieldName] = "$fieldName must be a valid email address";
            return false;
        }
        return true;
    }
    
    /**
     * Validate required field
     */
    public static function required($value, $fieldName = 'Field') {
        if (empty($value) || (is_string($value) && trim($value) === '')) {
            self::$errors[$fieldName] = "$fieldName is required";
            return false;
        }
        return true;
    }
    
    /**
     * Validate minimum length
     */
    public static function minLength($value, $min, $fieldName = 'Field') {
        if (strlen($value) < $min) {
            self::$errors[$fieldName] = "$fieldName must be at least $min characters";
            return false;
        }
        return true;
    }
    
    /**
     * Validate maximum length
     */
    public static function maxLength($value, $max, $fieldName = 'Field') {
        if (strlen($value) > $max) {
            self::$errors[$fieldName] = "$fieldName must not exceed $max characters";
            return false;
        }
        return true;
    }
    
    /**
     * Validate password strength
     */
    public static function password($value, $fieldName = 'Password') {
        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number, 1 special char
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value)) {
            self::$errors[$fieldName] = "$fieldName must be at least 8 characters with uppercase, lowercase, number, and special character";
            return false;
        }
        return true;
    }
    
    /**
     * Validate phone number
     */
    public static function phone($value, $fieldName = 'Phone') {
        // Basic phone validation (10-15 digits, optional + and -)
        if (!preg_match('/^\+?[\d\s\-()]{10,15}$/', $value)) {
            self::$errors[$fieldName] = "$fieldName must be a valid phone number";
            return false;
        }
        return true;
    }
    
    /**
     * Validate string
     */
    public static function string($value, $fieldName = 'Field') {
        if (!is_string($value)) {
            self::$errors[$fieldName] = "$fieldName must be a string";
            return false;
        }
        return true;
    }
    
    /**
     * Validate integer
     */
    public static function integer($value, $fieldName = 'Field') {
        if (!is_int($value) && !ctype_digit($value)) {
            self::$errors[$fieldName] = "$fieldName must be an integer";
            return false;
        }
        return true;
    }
    
    /**
     * Validate numeric
     */
    public static function numeric($value, $fieldName = 'Field') {
        if (!is_numeric($value)) {
            self::$errors[$fieldName] = "$fieldName must be numeric";
            return false;
        }
        return true;
    }
    
    /**
     * Sanitize input
     */
    public static function sanitize($value) {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Get JSON body
     */
    public static function getJsonBody() {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
    }
}
