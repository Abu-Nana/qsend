<?php
/**
 * Check local admin table structure
 */

require_once __DIR__ . '/../conn.php';

echo "=== Checking local admin table structure ===\n\n";

try {
    // Check if we're using PDO or mysqli
    if (isset($pdo)) {
        $stmt = $pdo->query("DESCRIBE admin");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $result = $conn->query("DESCRIBE admin");
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;
        }
    }
    
    echo "Columns in 'admin' table:\n";
    echo str_repeat("-", 60) . "\n";
    printf("%-20s %-20s\n", "Field", "Type");
    echo str_repeat("-", 60) . "\n";
    foreach ($columns as $col) {
        printf("%-20s %-20s\n", $col['Field'], $col['Type']);
    }
    echo "\n";
    
    // Get sample record
    if (isset($pdo)) {
        $stmt = $pdo->query("SELECT * FROM admin LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $result = $conn->query("SELECT * FROM admin LIMIT 1");
        $row = $result->fetch_assoc();
    }
    
    if ($row) {
        echo "Column names available:\n";
        echo str_repeat("-", 60) . "\n";
        foreach (array_keys($row) as $key) {
            echo "- " . $key . "\n";
        }
    }
    
    echo "\nDone!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

