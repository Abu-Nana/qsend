<?php
/**
 * Direct database connection to check admin table
 * This bypasses the config system for quick checking
 */

$host = 'deavirtualdb.cpmacs668r4j.eu-central-1.rds.amazonaws.com';
$user = 'qsenduser';
$pass = '+keU7c*Kdd%7#';
$db = 'qsenddb';

echo "=== Checking admin table structure ===\n\n";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get table structure
    $stmt = $pdo->query("DESCRIBE admin");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Columns in 'admin' table:\n";
    echo str_repeat("-", 60) . "\n";
    printf("%-20s %-20s %-10s\n", "Field", "Type", "Null");
    echo str_repeat("-", 60) . "\n";
    foreach ($columns as $col) {
        printf("%-20s %-20s %-10s\n", 
            $col['Field'], 
            $col['Type'], 
            $col['Null']
        );
    }
    echo "\n";
    
    // Get sample data (just column names)
    $stmt = $pdo->query("SELECT * FROM admin LIMIT 1");
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Sample query would use these columns:\n";
        echo str_repeat("-", 60) . "\n";
        foreach (array_keys($row) as $key) {
            echo "- " . $key . "\n";
        }
        echo "\n";
        
        // Show sample query structure
        echo "Example SELECT query:\n";
        echo str_repeat("-", 60) . "\n";
        echo "SELECT " . implode(", ", array_keys($row)) . "\n";
        echo "FROM admin\n";
        echo "WHERE [username_column] = 'value' AND [password_column] = MD5('password')\n";
    } else {
        echo "No records found in admin table.\n";
    }
    
    echo "\nDone!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

