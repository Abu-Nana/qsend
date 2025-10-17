<?php
/**
 * Legacy Database Configuration
 * 
 * This file now uses the centralized configuration system.
 * All database credentials are managed in /config/ directory.
 * 
 * @package QSEND
 * @version 2.0
 */

// Include centralized database configuration
require_once __DIR__ . '/../config/database.php';

// Legacy variables for backward compatibility
$server = DB_HOST;
$serverUser = DB_USER;
$serverPassword = DB_PASS;
$serverDB = DB_NAME;

// Use the centralized connection
$dbconnection = $conn;
?>