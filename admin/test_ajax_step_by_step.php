<?php
/**
 * Step-by-step AJAX test to find the exact error
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

// Start session
session_start();

// Set header for JSON response
header('Content-Type: application/json');

try {
    echo "=== STEP BY STEP TEST ===\n";
    
    // Step 1: Include database connection
    echo "Step 1: Including database config...\n";
    require_once __DIR__ . '/../config/database.php';
    echo "✅ Database config included\n";
    
    $conn = Database::getPDO();
    echo "✅ Database connection obtained\n";

    // Step 2: Check session
    echo "Step 2: Checking session...\n";
    if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Unauthorized access - no admin session'
        ]);
        exit;
    }
    echo "✅ Session check passed\n";

    // Step 3: Get user details
    echo "Step 3: Getting user details...\n";
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$_SESSION['admin']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!$user) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
        exit;
    }
    echo "✅ User lookup successful\n";

    // Step 4: Check vendor autoload
    echo "Step 4: Checking vendor autoload...\n";
    $vendor_loaded = false;
    if (file_exists('vendor3/autoload.php')) {
        require 'vendor3/autoload.php';
        $vendor_loaded = true;
        echo "✅ vendor3/autoload.php loaded\n";
    } else {
        echo "❌ vendor3/autoload.php not found\n";
    }

    // Step 5: Test class loading
    echo "Step 5: Testing class loading...\n";
    if ($vendor_loaded) {
        if (class_exists('setasign\Fpdi\Fpdi')) {
            echo "✅ FPDI class available\n";
        } else {
            echo "❌ FPDI class not available\n";
        }
        
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            echo "✅ PHPMailer class available\n";
        } else {
            echo "❌ PHPMailer class not available\n";
        }
    }

    // Step 6: Test directory creation
    echo "Step 6: Testing directory creation...\n";
    if (is_dir('temp')) {
        echo "✅ temp directory exists\n";
    } else {
        echo "❌ temp directory missing\n";
    }
    
    if (is_dir('deacompress')) {
        echo "✅ deacompress directory exists\n";
    } else {
        echo "❌ deacompress directory missing\n";
    }

    // Step 7: Test PDF files
    echo "Step 7: Testing PDF files...\n";
    if (is_dir('DEASemester')) {
        echo "✅ DEASemester directory exists\n";
        $pdf_files = glob('DEASemester/*.pdf');
        echo "Found " . count($pdf_files) . " PDF files\n";
    } else {
        echo "❌ DEASemester directory missing\n";
    }

    // Step 8: Test ZipArchive
    echo "Step 8: Testing ZipArchive...\n";
    if (class_exists('ZipArchive')) {
        echo "✅ ZipArchive class available\n";
    } else {
        echo "❌ ZipArchive class not available\n";
    }

    // Step 9: Test the actual query
    echo "Step 9: Testing database query...\n";
    $exam_session = "8:30am";
    $day = "Day 1";
    
    $script = "SELECT s.id, s.matric_number, s.study_center, s.study_center_code, s.course, s.exam_day, s.exam_session, s.exa_date, c.study_center_code, c.study_centre_email, c.phone_number, c.director 
                FROM student_registrations s
                INNER JOIN study_centers c 
                ON c.study_center_code = s.study_center_code 
                WHERE s.exam_session = ? 
                AND s.exam_day = ?
                GROUP BY c.study_center_code, s.course";

    $stmt = $conn->prepare($script);
    $stmt->execute([$exam_session, $day]);
    $query_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "✅ Query executed, found " . count($query_data) . " rows\n";

    if (empty($query_data)) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'No data found for the specified exam session and day'
        ]);
        exit;
    }

    // Success response
    ob_clean();
    echo json_encode([
        'success' => true,
        'message' => 'All tests passed successfully',
        'data_count' => count($query_data),
        'vendor_loaded' => $vendor_loaded,
        'sample_data' => $query_data[0] ?? null
    ]);

} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Exception: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
} catch (Error $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Fatal Error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>
