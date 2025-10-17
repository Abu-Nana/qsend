<?php
// Database configuration
$dbHost     = "localhost";
$dbUsername = "root";
$dbPassword = "25#wpdx20@DEA";
$dbName     = "NBRRI";

// Create database connection
global $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}