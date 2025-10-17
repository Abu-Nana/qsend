<?php
/**
 * Database Connection File
 * 
 * This file now uses the centralized configuration system.
 * All database credentials are managed in /config/ directory.
 * 
 * @package QSEND
 * @version 2.0
 */

// Include centralized database configuration
require_once __DIR__ . '/../../config/database.php';

// Fix MySQL SQL mode for compatibility
require_once __DIR__ . '/../../config/mysql_fix.php';

// $conn is automatically available from database.php
// No need to create connection here anymore
?>
