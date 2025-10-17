<?php
/**
 * MySQL Configuration Fix
 * 
 * This file handles MySQL sql_mode issues that can break GROUP BY queries
 * in older applications that weren't designed for strict SQL mode.
 * 
 * @package QSEND
 * @version 2.0
 */

// Include database connection
require_once __DIR__ . '/database.php';

/**
 * Fix MySQL SQL Mode for Compatibility
 * 
 * This function sets a more permissive SQL mode that's compatible
 * with older applications while maintaining security.
 */
function fixMySQLMode() {
    global $conn;
    
    try {
        // Set a more permissive SQL mode
        $sqlMode = "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION";
        $conn->query("SET sql_mode = '$sqlMode'");
        
        // Also set session variables for compatibility
        $conn->query("SET SESSION sql_mode = '$sqlMode'");
        
        return true;
    } catch (Exception $e) {
        error_log("MySQL mode fix failed: " . $e->getMessage());
        return false;
    }
}

// Automatically fix SQL mode when this file is included
fixMySQLMode();

// Also fix for PDO connections
try {
    $pdo = Database::getPDO();
    $pdo->exec("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
} catch (Exception $e) {
    error_log("PDO SQL mode fix failed: " . $e->getMessage());
}

?>

