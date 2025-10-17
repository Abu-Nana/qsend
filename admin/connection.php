<?php 
/**
 * Legacy Database Connection File
 * 
 * This file now uses the centralized configuration system.
 * All database credentials are managed in /config/ directory.
 * 
 * @package QSEND
 * @version 2.0
 */

// Include centralized database configuration
require_once __DIR__ . '/../config/database.php';

// $conn is automatically available from database.php
// Legacy variable for backward compatibility
$server_name = DB_HOST;
$user_name = DB_USER;
$password = DB_PASS;
$mysql_database = DB_NAME;

return $conn;
?>

