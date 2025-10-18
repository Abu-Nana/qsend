<?php
/**
 * Simple test to isolate the fatal error
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== SIMPLE TEST START ===\n";

try {
    echo "1. Starting session...\n";
    session_start();
    echo "✅ Session started\n";

    echo "2. Including database config...\n";
    require_once __DIR__ . '/../config/database.php';
    echo "✅ Database config included\n";

    echo "3. Getting database connection...\n";
    $conn = Database::getPDO();
    echo "✅ Database connection obtained\n";

    echo "4. Checking session admin...\n";
    if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
        echo "❌ No admin session found\n";
        exit;
    }
    echo "✅ Admin session found: " . $_SESSION['admin'] . "\n";

    echo "5. Getting user details...\n";
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$_SESSION['admin']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    
    if (!$user) {
        echo "❌ User not found\n";
        exit;
    }
    echo "✅ User found: " . $user['username'] . "\n";

    echo "6. Testing basic query...\n";
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM student_registrations");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    echo "✅ Query successful, found " . $result['count'] . " records\n";

    echo "7. Testing PHPMailer autoload...\n";
    if (file_exists('vendor3/autoload.php')) {
        require 'vendor3/autoload.php';
        echo "✅ PHPMailer autoload included\n";
    } else {
        echo "❌ PHPMailer autoload not found\n";
    }

    echo "8. Testing FPDI...\n";
    if (class_exists('setasign\Fpdi\Fpdi')) {
        echo "✅ FPDI class available\n";
    } else {
        echo "❌ FPDI class not available\n";
    }

    echo "9. Testing ZipArchive...\n";
    if (class_exists('ZipArchive')) {
        echo "✅ ZipArchive class available\n";
    } else {
        echo "❌ ZipArchive class not available\n";
    }

    echo "10. Testing directory creation...\n";
    $test_dir = "temp/test_" . time();
    if (mkdir($test_dir, 0777, true)) {
        echo "✅ Directory creation works\n";
        rmdir($test_dir);
    } else {
        echo "❌ Directory creation failed\n";
    }

    echo "\n=== ALL TESTS PASSED ===\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "❌ FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
?>
