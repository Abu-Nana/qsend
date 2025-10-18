<?php
/**
 * Debug script to check database tables
 */
session_start();
require_once __DIR__ . '/../config/database.php';

$conn = Database::getPDO();

try {
    // List all tables
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Database Tables:</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Check if specific tables exist
    $expected_tables = ['student_registration', 'student_registrations', 'study_centres', 'study_centers', 'files'];
    
    echo "<h2>Expected Tables Check:</h2>";
    echo "<ul>";
    foreach ($expected_tables as $table) {
        $exists = in_array($table, $tables) ? "✅ EXISTS" : "❌ MISSING";
        echo "<li>$table: $exists</li>";
    }
    echo "</ul>";
    
    // Show student_registrations table structure
    if (in_array('student_registrations', $tables)) {
        echo "<h2>student_registrations table structure:</h2>";
        $stmt = $conn->query("DESCRIBE student_registrations");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
    }
    
    // Show study_centers table structure
    if (in_array('study_centers', $tables)) {
        echo "<h2>study_centers table structure:</h2>";
        $stmt = $conn->query("DESCRIBE study_centers");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
