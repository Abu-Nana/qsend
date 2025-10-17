<?php
/**
 * PDO Database Connection
 * 
 * This file now uses the centralized configuration system.
 * All database credentials are managed in /config/ directory.
 * 
 * @package QSEND
 * @version 2.0
 */

// Include centralized database configuration
require_once __DIR__ . '/../../config/database.php';

// $pdo is automatically available from database.php
?>
