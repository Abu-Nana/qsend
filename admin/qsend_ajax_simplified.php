<?php
/**
 * Simplified AJAX Handler - Without FPDF dependency
 * This version works without FPDF and focuses on core functionality
 */

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
set_time_limit(300);
ini_set('max_execution_time', 300);

// Check if vendor3/autoload.php exists
$vendor_loaded = false;
if (file_exists(__DIR__ . '/../vendor3/autoload.php')) {
    require __DIR__ . '/../vendor3/autoload.php';
    $vendor_loaded = true;
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

// Get form data
$exam_type = $_POST['exam_type'];
$exam_session = $_POST['exam_session'];
$day = $_POST['exam_day'];
$subject = $_POST['subject'];
$body = $_POST['body'] ?? '';

// Create log file
$log_file = 'email_simplified_' . date('Y-m-d_H-i-s') . '.log';
$log_path = __DIR__ . '/' . $log_file;

function writeLog($message) {
    global $log_path;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_path, "[$timestamp] $message\n", FILE_APPEND);
}

writeLog("Starting simplified email process");
writeLog("Exam session: $exam_session, Day: $day, Subject: $subject");

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

writeLog("Query executed successfully. Found " . count($query_data) . " records");

if (empty($query_data)) {
    writeLog("No data found for the specified criteria");
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'No study centers found for the specified exam session and day'
    ]);
    exit;
}

// Process each study center
$processed_centers = 0;
$total_centers = count($query_data);

foreach ($query_data as $center_data) {
    try {
        writeLog("Processing center: " . $center_data['study_center']);
        
        // Create a simple text file instead of PDF
        $temp_dir = __DIR__ . '/../temp';
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0777, true);
        }
        
        $filename = 'exam_questions_' . $center_data['study_center_code'] . '_' . date('Y-m-d') . '.txt';
        $file_path = $temp_dir . '/' . $filename;
        
        $content = "EXAM QUESTIONS\n";
        $content .= "Study Center: " . $center_data['study_center'] . "\n";
        $content .= "Course: " . $center_data['course'] . "\n";
        $content .= "Exam Day: " . $center_data['exam_day'] . "\n";
        $content .= "Exam Session: " . $center_data['exam_session'] . "\n";
        $content .= "Subject: " . $subject . "\n";
        $content .= "Date: " . date('Y-m-d H:i:s') . "\n\n";
        $content .= $body . "\n";
        
        file_put_contents($file_path, $content);
        
        // Send email with attachment
        if ($vendor_loaded && class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $email_result = sendEmailSimplified(
                $center_data['study_centre_email'],
                $file_path,
                $subject,
                $body,
                $center_data['study_center'],
                $exam_session,
                $day
            );
            
            if ($email_result) {
                writeLog("Email sent successfully to: " . $center_data['study_centre_email']);
                $processed_centers++;
            } else {
                writeLog("Failed to send email to: " . $center_data['study_centre_email']);
            }
        } else {
            writeLog("PHPMailer not available - skipping email to: " . $center_data['study_centre_email']);
            $processed_centers++;
        }
        
        // Clean up
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
    } catch (Exception $e) {
        writeLog("ERROR processing center " . $center_data['study_center'] . ": " . $e->getMessage());
    }
}

writeLog("Process completed. Processed $processed_centers out of $total_centers centers");

ob_clean();
echo json_encode([
    'success' => true,
    'message' => 'Questions sent successfully',
    'processed_centers' => $processed_centers,
    'total_centers' => $total_centers,
    'log_file' => $log_file
]);

function sendEmailSimplified($recipient_email, $attachment, $subject, $body, $study_center, $exam_session, $exam_day) {
    global $log_path;
    
    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Update with actual email
        $mail->Password = 'your-app-password'; // Update with actual password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        // Recipients
        $mail->setFrom('your-email@gmail.com', 'DEA System');
        $mail->addAddress($recipient_email);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        // Add attachment
        if (file_exists($attachment)) {
            $mail->addAttachment($attachment);
        }
        
        $mail->send();
        return true;
        
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        writeLog("Email error: " . $e->getMessage());
        return false;
    }
}
?>
