<?php
/**
 * Debug version of qsend_ajax_original.php with better error handling
 */

// Enable error reporting and display
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start output buffering
ob_start();

// Start session
session_start();

// Include database connection
require_once __DIR__ . '/../config/database.php';
$conn = Database::getPDO();

// Check if user is logged in
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

// Get user details
$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->execute([$_SESSION['admin']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

if (!$user) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'User not found'
    ]);
    exit;
}

// Set header for JSON response
header('Content-Type: application/json');

// Prevent timeout for long processes
set_time_limit(300);
ini_set('max_execution_time', 300);

// Check if vendor3/autoload.php exists
$vendor_loaded = false;
if (file_exists(__DIR__ . '/../vendor3/autoload.php')) {
    require __DIR__ . '/../vendor3/autoload.php';
    $vendor_loaded = true;
} else {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Vendor autoload not found'
    ]);
    exit;
}

// Validate required fields
$required_fields = ['exam_type', 'exam_session', 'exam_day', 'subject'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => "Missing required field: $field"
        ]);
        exit;
    }
}

try {
    // Test class availability
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        throw new Exception('PHPMailer class not found');
    }
    
    if (!class_exists('setasign\Fpdi\Fpdi')) {
        throw new Exception('FPDI class not found');
    }
    
    // Test instantiation
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    $pdf = new \setasign\Fpdi\Fpdi();
    
    // If we get here, everything is working
    ob_clean();
    echo json_encode([
        'success' => true,
        'message' => 'All classes loaded successfully',
        'vendor_loaded' => $vendor_loaded,
        'phpmailer_available' => class_exists('PHPMailer\PHPMailer\PHPMailer'),
        'fpdi_available' => class_exists('setasign\Fpdi\Fpdi'),
        'fpdf_available' => class_exists('setasign\Fpdf\Fpdf')
    ]);
    
} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'vendor_loaded' => $vendor_loaded,
        'phpmailer_available' => class_exists('PHPMailer\PHPMailer\PHPMailer'),
        'fpdi_available' => class_exists('setasign\Fpdi\Fpdi'),
        'fpdf_available' => class_exists('setasign\Fpdf\Fpdf')
    ]);
} catch (Error $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Fatal Error: ' . $e->getMessage(),
        'vendor_loaded' => $vendor_loaded,
        'phpmailer_available' => class_exists('PHPMailer\PHPMailer\PHPMailer'),
        'fpdi_available' => class_exists('setasign\Fpdi\Fpdi'),
        'fpdf_available' => class_exists('setasign\Fpdf\Fpdf')
    ]);
}
?>