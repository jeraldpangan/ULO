<?php
/**
 * JWT Configuration
 * Custom JWT implementation for authentication
 */

define('JWT_SECRET', 'your-super-secret-jwt-key-change-in-production-minimum-32-characters');
define('JWT_ALGORITHM', 'HS256');
define('JWT_EXPIRY', 3600); // 1 hour in seconds
define('JWT_REFRESH_EXPIRY', 604800); // 7 days in seconds
