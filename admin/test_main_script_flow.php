<?php
/**
 * Test the exact flow of the main script to find the error
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

// Start session
session_start();

// Set header for JSON response
header('Content-Type: application/json');

// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering to catch any unexpected output
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
set_time_limit(300); // 5 minutes
ini_set('max_execution_time', 300);

// Check if vendor3/autoload.php exists
$vendor_loaded = false;
if (file_exists('vendor3/autoload.php')) {
    require 'vendor3/autoload.php';
    $vendor_loaded = true;
} else {
    // Create a simple log function
    function writeLog($message) {
        error_log($message);
    }
    writeLog("WARNING: vendor3/autoload.php not found - PDF processing will be limited");
}

// Create log file
$log_file = 'email_test_' . date('Y-m-d_H-i-s') . '.log';
$log_path = __DIR__ . '/' . $log_file;

function writeLog($message) {
    global $log_path;
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message\n";
    file_put_contents($log_path, $log_entry, FILE_APPEND | LOCK_EX);
}

try {
    writeLog("=== MAIN SCRIPT FLOW TEST STARTED ===");
    
    // Get POST data
    $exam_session = isset($_POST['exam_session']) ? $_POST['exam_session'] : '8:30am';
    $day = isset($_POST['exam_day']) ? $_POST['exam_day'] : 'Day 1';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : 'Testing on Cloud 8am';
    $body = isset($_POST['body']) ? $_POST['body'] : '';

    writeLog("POST Data - Session: $exam_session, Day: $day, Subject: $subject");

    // Validate required fields
    if (empty($exam_session) || empty($day) || empty($subject)) {
        writeLog("ERROR: Missing required fields");
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields'
        ]);
        exit;
    }

    // Use existing database connection
    $files_table = "files";

    writeLog("Database connection established");
    writeLog("Session admin ID: " . $_SESSION['admin']);

    // Query to get study centers and their courses
    $script = "SELECT s.id, s.matric_number, s.study_center, s.study_center_code, s.course, s.exam_day, s.exam_session, s.exa_date, c.study_center_code, c.study_centre_email, c.phone_number, c.director 
                FROM student_registrations s
                INNER JOIN study_centers c 
                ON c.study_center_code = s.study_center_code 
                WHERE s.exam_session = ? 
                AND s.exam_day = ?
                GROUP BY c.study_center_code, s.course";

    try {
        $stmt = $conn->prepare($script);
        writeLog("Prepared statement: $script");

        if (!$stmt) {
            writeLog("ERROR: Database query preparation failed");
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Database query preparation failed'
            ]);
            exit;
        }

        $stmt->execute([$exam_session, $day]);
        $query_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        writeLog("ERROR: Database query failed: " . $e->getMessage());
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
        exit;
    }
    
    writeLog("Query executed with session: '$exam_session', day: '$day'");
    writeLog("Query returned " . count($query_data) . " rows");

    if (empty($query_data)) {
        writeLog("ERROR: No data found for exam session and day");
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'No data found for the specified exam session and day'
        ]);
        exit;
    }

    // Organize data by center
    $centers = [];
    if (count($query_data) > 0) {
        foreach ($query_data as $obj) {
            $centers[$obj['study_center_code']][] = $obj;
        }
        writeLog("Found " . count($centers) . " study centers");
    } else {
        writeLog("ERROR: No data found for exam session and day");
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'No data found for the specified exam session and day'
        ]);
        exit;
    }

    // Initialize response data
    $recipients = [];
    $sent_count = 0;
    $failed_count = 0;
    $total_centers = count($centers);

    // User information
    $username = $user['username'];
    $firstName = $user['firstname'];
    $lastName = $user['lastname'];
    $fullName = $username . ' - ' . $firstName . ' ' . $lastName;
    $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown IP';

    writeLog("Processing $total_centers study centers");
    writeLog("User info - Username: $username, Full Name: $fullName");

    // Process first center only for testing
    $center_count = 0;
    foreach ($centers as $center => $value) {
        $center_count++;
        if ($center_count > 1) break; // Only process first center for testing
        
        writeLog("Processing center: $center");
        
        $recipient_data = [
            'study_center' => '',
            'center_code' => $center,
            'email' => '',
            'director' => '',
            'password' => '',
            'status' => 'failed',
            'error_message' => ''
        ];

        try {
            // Get center information
            $exam_session_val = isset($value[0]['exam_session']) ? $value[0]['exam_session'] : '_session';
            $study_center = isset($value[0]['study_center']) ? $value[0]['study_center'] : '_session';
            $exam_day = isset($value[0]['exam_day']) ? $value[0]['exam_day'] : '_session';
            $director = isset($value[0]['director']) ? $value[0]['director'] : 'Unknown';
            $email = isset($value[0]['study_centre_email']) ? $value[0]['study_centre_email'] : '';

            writeLog("Center: $study_center, Email: $email, Director: $director");
            
            if (empty($email)) {
                writeLog("WARNING: No email found for center $center");
                $recipient_data['error_message'] = 'No email address found';
                $recipients[] = $recipient_data;
                $failed_count++;
                continue;
            }

            $recipient_data['study_center'] = $study_center;
            $recipient_data['director'] = $director;
            $recipient_data['email'] = $email;

            // Generate password
            $password = "dea" . rand(1, 1000000);
            $recipient_data['password'] = $password;

            writeLog("Generated password: $password");

            // Create file name
            $file_name = str_replace(':', '_', str_replace(' ', '_', $study_center . $center . "_" . $exam_session_val . '_' . $exam_day));
            $file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $file_name);
            $zipfile = "deacompress/" . $file_name . ".zip";
            $folder_path = "temp/" . $file_name;

            writeLog("ZIP file path: $zipfile");

            // Create temp directory
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0777, true);
                writeLog("Created temp directory: $folder_path");
            }

            writeLog("SUCCESS: Basic processing completed for center $center");
            
        } catch (Exception $e) {
            writeLog("ERROR: Processing failed for center $center: " . $e->getMessage());
            $recipient_data['error_message'] = $e->getMessage();
            $recipients[] = $recipient_data;
            $failed_count++;
        }
    }

    // Success response
    ob_clean();
    echo json_encode([
        'success' => true,
        'message' => 'Main script flow test completed successfully',
        'total_centers' => $total_centers,
        'processed_centers' => $center_count,
        'log_file' => $log_file
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
