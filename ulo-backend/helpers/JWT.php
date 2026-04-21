<?php
/**
 * JWT Helper Class
 * Custom JWT implementation for authentication
 */

require_once __DIR__ . '/../config/JWT.php';

class JWT {
    
    /**
     * Encode JWT token
     * 
     * @param array $payload The data to encode
     * @param int $expiry Token expiry time in seconds
     * @return string JWT token
     */
    public static function encode($payload, $expiry = null) {
        if ($expiry === null) {
            $expiry = JWT_EXPIRY;
        }
        
        // Add issued at and expiry times
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiry;
        
        // Create header
        $header = [
            'alg' => JWT_ALGORITHM,
            'typ' => 'JWT'
        ];
        
        // Encode header and payload
        $header_encoded = self::base64UrlEncode(json_encode($header));
        $payload_encoded = self::base64UrlEncode(json_encode($payload));
        
        // Create signature
        $message = $header_encoded . '.' . $payload_encoded;
        $signature = hash_hmac('sha256', $message, JWT_SECRET, true);
        $signature_encoded = self::base64UrlEncode($signature);
        
        return $message . '.' . $signature_encoded;
    }
    
    /**
     * Decode and verify JWT token
     * 
     * @param string $token JWT token to verify
     * @return array Decoded payload if valid
     * @throws Exception If token is invalid or expired
     */
    public static function decode($token) {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            throw new Exception('Invalid token format');
        }
        
        list($header_encoded, $payload_encoded, $signature_encoded) = $parts;
        
        // Verify signature
        $message = $header_encoded . '.' . $payload_encoded;
        $signature = hash_hmac('sha256', $message, JWT_SECRET, true);
        $signature_encoded_check = self::base64UrlEncode($signature);
        
        // Use timing-safe comparison
        if (!hash_equals($signature_encoded, $signature_encoded_check)) {
            throw new Exception('Invalid token signature');
        }
        
        // Decode payload
        $payload_json = self::base64UrlDecode($payload_encoded);
        $payload = json_decode($payload_json, true);
        
        if (!$payload) {
            throw new Exception('Invalid payload format');
        }
        
        // Check expiry
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new Exception('Token has expired');
        }
        
        return $payload;
    }
    
    /**
     * Base64 URL encode
     * 
     * @param string $data Data to encode
     * @return string Base64 URL encoded string
     */
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Base64 URL decode
     * 
     * @param string $data Data to decode
     * @return string Decoded data
     */
    private static function base64UrlDecode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
