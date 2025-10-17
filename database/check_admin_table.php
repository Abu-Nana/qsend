<?php
/**
 * Quick script to check admin table structure
 * Run this to see what columns exist in your admin table
 */

require_once __DIR__ . '/../config/database.php';

$conn = Database::getPDO();

echo "=== Checking admin table structure ===\n\n";

try {
    // Get table structure
    $stmt = $conn->query("DESCRIBE admin");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Columns in 'admin' table:\n";
    echo str_repeat("-", 50) . "\n";
    foreach ($columns as $col) {
        echo sprintf("%-20s %-15s\n", $col['Field'], $col['Type']);
    }
    echo "\n";
    
    // Get sample data (just structure, not actual values)
    $stmt = $conn->query("SELECT * FROM admin LIMIT 1");
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Available column names:\n";
        echo str_repeat("-", 50) . "\n";
        foreach (array_keys($row) as $key) {
            echo "- " . $key . "\n";
        }
    }
    
    echo "\nDone!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

