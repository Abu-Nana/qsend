<?php
/**
 * Centralized Database Configuration
 * 
 * This file handles environment detection and loads appropriate credentials.
 * DO NOT commit config.local.php or config.production.php to version control.
 * 
 * @package QSEND
 * @version 2.0
 */

// Determine environment based on server name or environment variable
function detectEnvironment() {
    // Check environment variable first
    if (getenv('APP_ENV')) {
        return getenv('APP_ENV');
    }
    
    // Check for localhost or development indicators
    $serverName = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
    $isLocal = (
        $serverName === 'localhost' || 
        $serverName === '127.0.0.1' || 
        strpos($serverName, '.local') !== false ||
        strpos($serverName, 'dev.') !== false
    );
    
    return $isLocal ? 'local' : 'production';
}

// Get environment
$environment = detectEnvironment();

// Load environment-specific configuration
$configFile = __DIR__ . '/config.' . $environment . '.php';

if (!file_exists($configFile)) {
    die("Configuration file not found: {$configFile}. Please create config.{$environment}.php based on config.example.php");
}

require_once $configFile;

/**
 * Database Connection Class
 * Provides both MySQLi and PDO connections with error handling
 */
class Database {
    private static $mysqliInstance = null;
    private static $pdoInstance = null;
    
    /**
     * Get MySQLi Connection
     * @return mysqli
     */
    public static function getMySQLi() {
        if (self::$mysqliInstance === null) {
            self::$mysqliInstance = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if (self::$mysqliInstance->connect_error) {
                self::logError("MySQLi Connection failed: " . self::$mysqliInstance->connect_error);
                die("Database connection failed. Please contact system administrator.");
            }
            
            // Set charset to UTF-8
            self::$mysqliInstance->set_charset("utf8mb4");
        }
        
        return self::$mysqliInstance;
    }
    
    /**
     * Get PDO Connection
     * @return PDO
     */
    public static function getPDO() {
        if (self::$pdoInstance === null) {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                self::$pdoInstance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                self::logError("PDO Connection failed: " . $e->getMessage());
                die("Database connection failed. Please contact system administrator.");
            }
        }
        
        return self::$pdoInstance;
    }
    
    /**
     * Log errors to file
     * @param string $message
     */
    private static function logError($message) {
        $logFile = __DIR__ . '/../logs/db_errors.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
        @error_log($logMessage, 3, $logFile);
    }
    
    /**
     * Close all connections
     */
    public static function closeConnections() {
        if (self::$mysqliInstance !== null) {
            self::$mysqliInstance->close();
            self::$mysqliInstance = null;
        }
        
        self::$pdoInstance = null;
    }
}

// Legacy compatibility - create global $conn variable
$conn = Database::getMySQLi();

// Legacy compatibility - create global $pdo variable for PDO users
$pdo = Database::getPDO();

// Clean up on shutdown
register_shutdown_function(function() {
    Database::closeConnections();
});


