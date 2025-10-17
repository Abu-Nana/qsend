<?php
/**
 * Password Migration Utility
 * 
 * This script helps migrate passwords from MD5 to bcrypt.
 * 
 * WARNING: This script should be run ONCE and then removed or secured.
 * DO NOT leave this accessible on a production server!
 * 
 * Usage:
 * 1. Run this script from command line: php password_migration.php
 * 2. Or access via browser with authentication
 * 
 * @package QSEND
 * @version 2.0
 */

// Uncomment these lines if running from CLI
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Security check - only allow access with this secret key
$SECRET_KEY = 'CHANGE_THIS_SECRET_KEY_12345'; // CHANGE THIS!

if (php_sapi_name() !== 'cli') {
    // If running from web, require secret key
    if (!isset($_GET['key']) || $_GET['key'] !== $SECRET_KEY) {
        die("Unauthorized access. This utility requires authentication.");
    }
}

// Include required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/Security.php';

echo "===========================================\n";
echo "QSEND Password Migration Utility\n";
echo "===========================================\n\n";

/**
 * Migration Options
 */
$MIGRATION_MODE = 'REPORT'; // Options: 'REPORT', 'SET_TEMPORARY', 'INTERACTIVE'

/**
 * Report Mode: Just shows which passwords need migration
 */
function reportMode($conn) {
    echo "Mode: REPORT ONLY (no changes will be made)\n\n";
    
    // Check admin table
    echo "Checking 'admin' table...\n";
    $result = $conn->query("SELECT id, username, password FROM admin");
    $totalAdmin = 0;
    $needsMigrationAdmin = 0;
    
    while ($row = $result->fetch_assoc()) {
        $totalAdmin++;
        if (Security::needsRehash($row['password'])) {
            $needsMigrationAdmin++;
            echo "  ❌ User: {$row['username']} (ID: {$row['id']}) - Needs migration\n";
        } else {
            echo "  ✅ User: {$row['username']} (ID: {$row['id']}) - Already bcrypt\n";
        }
    }
    
    echo "\nAdmin Table Summary:\n";
    echo "  Total users: {$totalAdmin}\n";
    echo "  Need migration: {$needsMigrationAdmin}\n";
    echo "  Already secure: " . ($totalAdmin - $needsMigrationAdmin) . "\n\n";
    
    // Check usr_acct table
    echo "Checking 'usr_acct' table...\n";
    $pdo = Database::getPDO();
    $stmt = $pdo->query("SELECT usr_name, acct_name, usr_passwords FROM usr_acct");
    $totalUsers = 0;
    $needsMigrationUsers = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $totalUsers++;
        if (Security::needsRehash($row['usr_passwords'])) {
            $needsMigrationUsers++;
            echo "  ❌ User: {$row['usr_name']} ({$row['acct_name']}) - Needs migration\n";
        } else {
            echo "  ✅ User: {$row['usr_name']} ({$row['acct_name']}) - Already bcrypt\n";
        }
    }
    
    echo "\nUsr_Acct Table Summary:\n";
    echo "  Total users: {$totalUsers}\n";
    echo "  Need migration: {$needsMigrationUsers}\n";
    echo "  Already secure: " . ($totalUsers - $needsMigrationUsers) . "\n\n";
    
    echo "===========================================\n";
    echo "Total users needing migration: " . ($needsMigrationAdmin + $needsMigrationUsers) . "\n";
    echo "===========================================\n\n";
    
    if (($needsMigrationAdmin + $needsMigrationUsers) > 0) {
        echo "RECOMMENDATION:\n";
        echo "1. Since passwords will be automatically upgraded on user login,\n";
        echo "   you can wait for users to login naturally (RECOMMENDED)\n";
        echo "2. Or set temporary passwords and notify users\n";
        echo "   (Change MIGRATION_MODE to 'SET_TEMPORARY')\n";
    }
}

/**
 * Set Temporary Password Mode: Sets a temporary password for all users
 */
function setTemporaryMode($conn) {
    echo "Mode: SET TEMPORARY PASSWORDS\n";
    echo "WARNING: This will change passwords for all users with MD5 hashes!\n\n";
    
    if (php_sapi_name() === 'cli') {
        echo "Are you sure you want to continue? (yes/no): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim($line) !== 'yes') {
            echo "Migration cancelled.\n";
            return;
        }
    }
    
    // Generate a random temporary password
    $tempPassword = 'TempPass' . rand(1000, 9999) . '!';
    $hashedPassword = Security::hashPassword($tempPassword);
    
    echo "Temporary password: {$tempPassword}\n";
    echo "Please save this password to share with users!\n\n";
    
    // Migrate admin table
    echo "Migrating 'admin' table...\n";
    $result = $conn->query("SELECT id, username, password FROM admin");
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
    $migrated = 0;
    
    while ($row = $result->fetch_assoc()) {
        if (Security::needsRehash($row['password'])) {
            $stmt->bind_param("si", $hashedPassword, $row['id']);
            $stmt->execute();
            $migrated++;
            echo "  ✅ Migrated: {$row['username']}\n";
        }
    }
    
    echo "Migrated {$migrated} admin users.\n\n";
    
    // Migrate usr_acct table
    echo "Migrating 'usr_acct' table...\n";
    $pdo = Database::getPDO();
    $selectStmt = $pdo->query("SELECT usr_name, usr_passwords FROM usr_acct");
    $updateStmt = $pdo->prepare("UPDATE usr_acct SET usr_passwords = :password WHERE usr_name = :username");
    $migrated = 0;
    
    while ($row = $selectStmt->fetch(PDO::FETCH_ASSOC)) {
        if (Security::needsRehash($row['usr_passwords'])) {
            $updateStmt->execute([
                ':password' => $hashedPassword,
                ':username' => $row['usr_name']
            ]);
            $migrated++;
            echo "  ✅ Migrated: {$row['usr_name']}\n";
        }
    }
    
    echo "Migrated {$migrated} application users.\n\n";
    
    echo "===========================================\n";
    echo "IMPORTANT: Temporary Password\n";
    echo "===========================================\n";
    echo "Password: {$tempPassword}\n";
    echo "\nPlease notify all users to:\n";
    echo "1. Login with this temporary password\n";
    echo "2. Change their password immediately\n";
    echo "===========================================\n";
}

/**
 * Interactive Mode: Let's you set passwords for specific users
 */
function interactiveMode($conn) {
    echo "Mode: INTERACTIVE\n";
    echo "This mode is for manual migration of specific users.\n\n";
    
    // This is a placeholder - implement as needed
    echo "Interactive mode not yet implemented.\n";
    echo "Use REPORT or SET_TEMPORARY modes instead.\n";
}

// Run the migration
try {
    $conn = Database::getMySQLi();
    
    switch ($MIGRATION_MODE) {
        case 'REPORT':
            reportMode($conn);
            break;
        case 'SET_TEMPORARY':
            setTemporaryMode($conn);
            break;
        case 'INTERACTIVE':
            interactiveMode($conn);
            break;
        default:
            echo "Invalid MIGRATION_MODE. Use: REPORT, SET_TEMPORARY, or INTERACTIVE\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\nMigration utility completed.\n";

// Security reminder
if (php_sapi_name() !== 'cli') {
    echo "\n<hr>\n";
    echo "<strong style='color:red'>SECURITY WARNING:</strong> ";
    echo "Remember to delete or secure this utility script after use!";
}


