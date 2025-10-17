<?php
/**
 * Example Configuration File
 * 
 * Copy this file to:
 * - config.local.php for local development
 * - config.production.php for production environment
 * 
 * Then update the values according to your environment.
 * 
 * @package QSEND
 * @version 2.0
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_username');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Application Configuration
define('APP_ENV', 'production'); // or 'local'
define('APP_DEBUG', false); // Set to true only in development
define('APP_URL', 'https://your-domain.com');

// Security Configuration
define('SESSION_LIFETIME', 3600); // Session lifetime in seconds
define('PASSWORD_MIN_LENGTH', 8); // Minimum password length
define('MAX_LOGIN_ATTEMPTS', 5); // Maximum failed login attempts
define('LOGIN_LOCKOUT_TIME', 900); // Lockout time in seconds (15 minutes)

// Error Reporting
ini_set('display_errors', APP_DEBUG ? 1 : 0);
error_reporting(E_ALL);

// Timezone
date_default_timezone_set('Africa/Lagos');


