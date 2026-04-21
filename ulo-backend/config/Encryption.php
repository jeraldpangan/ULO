<?php
/**
 * Encryption Configuration
 * AES-256-GCM encryption for sensitive data
 */

// Generate a secure 256-bit (32-byte) encryption key
// This should match the key used throughout the application
// For production, use environment variables or secure configuration management
define('ENCRYPTION_KEY', hex2bin('a4f8d3c9b2e5f1a7c8d4e9f2a3b5c7d8a1f3e5c7b9d2f4a6c8e1f3a5b7d9'));

// Encryption algorithm
define('ENCRYPTION_CIPHER', 'aes-256-gcm');

// IV size for GCM (12 bytes is standard for GCM)
define('ENCRYPTION_IV_SIZE', 12);

// Authentication tag size (16 bytes for AES-256-GCM)
define('ENCRYPTION_TAG_SIZE', 16);
