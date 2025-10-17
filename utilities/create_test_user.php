<?php
/**
 * Create Test User Utility
 * 
 * This script creates a test user for testing the new authentication system.
 * Run once, then delete or secure this file.
 * 
 * Usage: php create_test_user.php
 * 
 * @package QSEND
 * @version 2.0
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=========================================\n";
echo "QSEND - Create Test User Utility\n";
echo "=========================================\n\n";

// Include configuration
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/Security.php';

$conn = Database::getMySQLi();
$pdo = Database::getPDO();

// Test user credentials
$TEST_USERS = [
    'admin' => [
        'id' => 'test001',
        'username' => 'testadmin',
        'password' => 'TestPass123!',
        'firstname' => 'Test',
        'lastname' => 'Administrator',
        'cat' => 'admin',
        'photo' => 'profile.jpg'
    ],
    'user' => [
        'usr_name' => 'testuser@test.com',
        'password' => 'TestPass123!',
        'acct_name' => 'Test User',
        'usr_cat' => 'ADMIN',
        'unit_code' => 'TEST01',
        'pwd_reset' => 0
    ]
];

echo "This utility will create test users for testing the authentication system.\n\n";

// Check if tables exist
$tablesExist = [];
$result = $conn->query("SHOW TABLES LIKE 'admin'");
$tablesExist['admin'] = $result->num_rows > 0;

$stmt = $pdo->query("SHOW TABLES LIKE 'usr_acct'");
$tablesExist['usr_acct'] = $stmt->rowCount() > 0;

echo "Database Tables Found:\n";
echo "  - admin table: " . ($tablesExist['admin'] ? "✅ Found" : "❌ Not found") . "\n";
echo "  - usr_acct table: " . ($tablesExist['usr_acct'] ? "✅ Found" : "❌ Not found") . "\n\n";

// Create test admin user
if ($tablesExist['admin']) {
    echo "Creating test admin user...\n";
    
    $id = $TEST_USERS['admin']['id'];
    $username = $TEST_USERS['admin']['username'];
    $password = $TEST_USERS['admin']['password'];
    $firstname = $TEST_USERS['admin']['firstname'];
    $lastname = $TEST_USERS['admin']['lastname'];
    $cat = $TEST_USERS['admin']['cat'];
    $photo = $TEST_USERS['admin']['photo'];
    $created_on = date('Y-m-d H:i:s');
    
    // Hash password with bcrypt
    $hashedPassword = Security::hashPassword($password);
    
    // Check if user already exists
    $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "  ⚠️  User '$username' already exists. Updating password...\n";
        
        $stmt = $conn->prepare("UPDATE admin SET password = ?, firstname = ?, lastname = ?, cat = ? WHERE username = ?");
        $stmt->bind_param("sssss", $hashedPassword, $firstname, $lastname, $cat, $username);
        $stmt->execute();
        echo "  ✅ Password updated for user '$username'\n";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO admin (id, username, password, firstname, lastname, cat, photo, created_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $id, $username, $hashedPassword, $firstname, $lastname, $cat, $photo, $created_on);
        
        if ($stmt->execute()) {
            echo "  ✅ Test admin user created successfully!\n";
        } else {
            echo "  ❌ Error creating admin user: " . $stmt->error . "\n";
        }
    }
    
    echo "\n";
    echo "  Admin Login Credentials:\n";
    echo "  ========================\n";
    echo "  Username: $username\n";
    echo "  Password: $password\n";
    echo "  URL: http://localhost/qsend\n";
    echo "\n";
}

// Create test usr_acct user
if ($tablesExist['usr_acct']) {
    echo "Creating test application user...\n";
    
    $usr_name = $TEST_USERS['user']['usr_name'];
    $password = $TEST_USERS['user']['password'];
    $acct_name = $TEST_USERS['user']['acct_name'];
    $usr_cat = $TEST_USERS['user']['usr_cat'];
    $unit_code = $TEST_USERS['user']['unit_code'];
    $pwd_reset = $TEST_USERS['user']['pwd_reset'];
    
    // Hash password with bcrypt
    $hashedPassword = Security::hashPassword($password);
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT usr_name FROM usr_acct WHERE usr_name = :usr_name");
    $stmt->execute([':usr_name' => $usr_name]);
    
    if ($stmt->rowCount() > 0) {
        echo "  ⚠️  User '$usr_name' already exists. Updating password...\n";
        
        $stmt = $pdo->prepare("UPDATE usr_acct SET usr_passwords = :password, acct_name = :acct_name WHERE usr_name = :usr_name");
        $stmt->execute([
            ':password' => $hashedPassword,
            ':acct_name' => $acct_name,
            ':usr_name' => $usr_name
        ]);
        echo "  ✅ Password updated for user '$usr_name'\n";
    } else {
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO usr_acct (usr_name, usr_passwords, acct_name, usr_cat, unit_code, pwd_reset) VALUES (:usr_name, :password, :acct_name, :usr_cat, :unit_code, :pwd_reset)");
        
        if ($stmt->execute([
            ':usr_name' => $usr_name,
            ':password' => $hashedPassword,
            ':acct_name' => $acct_name,
            ':usr_cat' => $usr_cat,
            ':unit_code' => $unit_code,
            ':pwd_reset' => $pwd_reset
        ])) {
            echo "  ✅ Test application user created successfully!\n";
        } else {
            echo "  ❌ Error creating application user\n";
        }
    }
    
    echo "\n";
    echo "  Application Login Credentials:\n";
    echo "  ==============================\n";
    echo "  Username: $usr_name\n";
    echo "  Password: $password\n";
    echo "  Role: $usr_cat\n";
    echo "  URL: http://localhost/qsend (use AJAX login)\n";
    echo "\n";
}

echo "=========================================\n";
echo "Test Users Created Successfully!\n";
echo "=========================================\n\n";

echo "IMPORTANT SECURITY NOTES:\n";
echo "1. These are TEST accounts only - use for testing\n";
echo "2. Delete or change passwords before production\n";
echo "3. Remove this script after use: rm utilities/create_test_user.php\n\n";

echo "Password Security:\n";
echo "  - Passwords are hashed with bcrypt (cost factor: 12)\n";
echo "  - Hash example: " . substr($hashedPassword, 0, 40) . "...\n";
echo "  - These passwords are STRONG and SECURE\n\n";

echo "Next Steps:\n";
echo "1. Visit http://localhost/qsend\n";
echo "2. Login with the credentials above\n";
echo "3. Test the new security features\n";
echo "4. Try wrong password 5 times to test rate limiting\n\n";

echo "Done!\n";

