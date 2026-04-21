<?php
/**
 * Encryptor Helper Class
 * Handles AES-256-GCM encryption and decryption
 */

require_once __DIR__ . '/../config/Encryption.php';

class Encryptor {
    
    /**
     * Encrypt data using AES-256-GCM
     * 
     * @param string $plaintext The data to encrypt
     * @return array Containing 'encrypted', 'iv', and 'tag'
     */
    public static function encrypt($plaintext) {
        // Generate random IV
        $iv = openssl_random_pseudo_bytes(ENCRYPTION_IV_SIZE, $strong);
        
        if (!$strong) {
            throw new Exception('Insufficient entropy for IV generation');
        }
        
        // Initialize authentication tag variable
        $tag = '';
        
        // Encrypt the data
        $encrypted = openssl_encrypt(
            $plaintext,
            ENCRYPTION_CIPHER,
            ENCRYPTION_KEY,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            ENCRYPTION_TAG_SIZE
        );
        
        if ($encrypted === false) {
            throw new Exception('Encryption failed: ' . openssl_error_string());
        }
        
        return [
            'encrypted' => base64_encode($encrypted),
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag)
        ];
    }
    
    /**
     * Decrypt data using AES-256-GCM
     * 
     * @param string $encrypted The encrypted data (base64 encoded)
     * @param string $iv The initialization vector (base64 encoded)
     * @param string $tag The authentication tag (base64 encoded)
     * @return string The decrypted plaintext
     */
    public static function decrypt($encrypted, $iv, $tag) {
        // Decode from base64
        $encrypted_data = base64_decode($encrypted, true);
        $iv_data = base64_decode($iv, true);
        $tag_data = base64_decode($tag, true);
        
        // Validate decoded data
        if ($encrypted_data === false || $iv_data === false || $tag_data === false) {
            throw new Exception('Invalid base64 encoding');
        }
        
        // Decrypt the data
        $decrypted = openssl_decrypt(
            $encrypted_data,
            ENCRYPTION_CIPHER,
            ENCRYPTION_KEY,
            OPENSSL_RAW_DATA,
            $iv_data,
            $tag_data
        );
        
        if ($decrypted === false) {
            throw new Exception('Decryption failed: Data may have been tampered with');
        }
        
        return $decrypted;
    }
    
    /**
     * Store encrypted data in a format suitable for database
     * 
     * @param string $plaintext The data to encrypt
     * @return string JSON containing encrypted data, IV, and tag
     */
    public static function encryptForStorage($plaintext) {
        $encrypted_data = self::encrypt($plaintext);
        return json_encode($encrypted_data);
    }
    
    /**
     * Retrieve and decrypt data from database storage
     * 
     * @param string $json_data JSON containing encrypted data, IV, and tag
     * @return string The decrypted plaintext
     */
    public static function decryptFromStorage($json_data) {
        $encrypted_data = json_decode($json_data, true);
        
        if (!isset($encrypted_data['encrypted']) || !isset($encrypted_data['iv']) || !isset($encrypted_data['tag'])) {
            throw new Exception('Invalid encrypted data format');
        }
        
        return self::decrypt($encrypted_data['encrypted'], $encrypted_data['iv'], $encrypted_data['tag']);
    }
}
