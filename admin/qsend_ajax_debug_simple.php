<?php
/**
 * Debug version of simplified AJAX handler with extensive logging
 */

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start output buffering
ob_start();

// Start session
session_start();

echo "<h2>Debug AJAX Handler</h2>";

// Include database connection
echo "<h3>1. Including database connection</h3>";
require_once __DIR__ . '/../config/database.php';
echo "✅ Database connection included<br>";

$conn = Database::getPDO();
echo "✅ PDO connection established<br>";

// Check if user is logged in
echo "<h3>2. Checking authentication</h3>";
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    echo "❌ User not logged in<br>";
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}
echo "✅ User is logged in: " . $_SESSION['admin'] . "<br>";

// Get user details
echo "<h3>3. Getting user details</h3>";
$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->execute([$_SESSION['admin']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

if (!$user) {
    echo "❌ User not found in database<br>";
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'User not found'
    ]);
    exit;
}
echo "✅ User found: " . $user['username'] . "<br>";

// Set header for JSON response
header('Content-Type: application/json');

// Prevent timeout for long processes
set_time_limit(300);
ini_set('max_execution_time', 300);

// Check if vendor3/autoload.php exists
echo "<h3>4. Checking vendor autoload</h3>";
$vendor_loaded = false;
if (file_exists(__DIR__ . '/../vendor3/autoload.php')) {
    require __DIR__ . '/../vendor3/autoload.php';
    $vendor_loaded = true;
    echo "✅ Vendor autoload loaded<br>";
} else {
    echo "❌ Vendor autoload not found<br>";
}

// Validate required fields
echo "<h3>5. Validating form data</h3>";
$required_fields = ['exam_type', 'exam_session', 'exam_day', 'subject'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo "❌ Missing field: $field<br>";
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => "Missing required field: $field"
        ]);
        exit;
    }
    echo "✅ Field '$field' has value: " . $_POST[$field] . "<br>";
}

// Get form data
$exam_type = $_POST['exam_type'];
$exam_session = $_POST['exam_session'];
$day = $_POST['exam_day'];
$subject = $_POST['subject'];
$body = $_POST['body'] ?? '';

echo "<h3>6. Form data extracted</h3>";
echo "Exam type: $exam_type<br>";
echo "Exam session: $exam_session<br>";
echo "Exam day: $day<br>";
echo "Subject: $subject<br>";
echo "Body: $body<br>";

// Create log file
echo "<h3>7. Creating log file</h3>";
$log_file = 'email_debug_' . date('Y-m-d_H-i-s') . '.log';
$log_path = __DIR__ . '/' . $log_file;

function writeLog($message) {
    global $log_path;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_path, "[$timestamp] $message\n", FILE_APPEND);
}

writeLog("Starting debug email process");
writeLog("Exam session: $exam_session, Day: $day, Subject: $subject");

// Query to get study centers and their courses
echo "<h3>8. Executing database query</h3>";
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
        echo "❌ Database query preparation failed<br>";
        ob_clean();
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
    echo "❌ Database query failed: " . $e->getMessage() . "<br>";
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
}

writeLog("Query executed successfully. Found " . count($query_data) . " records");
echo "✅ Query executed successfully. Found " . count($query_data) . " records<br>";

if (empty($query_data)) {
    writeLog("No data found for the specified criteria");
    echo "❌ No data found for the specified criteria<br>";
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'No study centers found for the specified exam session and day'
    ]);
    exit;
}

echo "<h3>9. Processing centers</h3>";
// Process each study center
$processed_centers = 0;
$total_centers = count($query_data);

foreach ($query_data as $center_data) {
    try {
        writeLog("Processing center: " . $center_data['study_center']);
        echo "Processing center: " . $center_data['study_center'] . "<br>";
        
        // Create a simple text file instead of PDF
        $temp_dir = __DIR__ . '/../temp';
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0777, true);
            echo "Created temp directory<br>";
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
        echo "Created file: $filename<br>";
        
        // For now, just simulate email sending
        writeLog("Simulating email send to: " . $center_data['study_centre_email']);
        echo "Simulating email send to: " . $center_data['study_centre_email'] . "<br>";
        
        $processed_centers++;
        echo "✅ Center processed successfully<br>";
        
        // Clean up
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
    } catch (Exception $e) {
        writeLog("ERROR processing center " . $center_data['study_center'] . ": " . $e->getMessage());
        echo "❌ Error processing center " . $center_data['study_center'] . ": " . $e->getMessage() . "<br>";
    }
}

writeLog("Process completed. Processed $processed_centers out of $total_centers centers");
echo "<h3>10. Process completed</h3>";
echo "Processed $processed_centers out of $total_centers centers<br>";

ob_clean();
echo json_encode([
    'success' => true,
    'message' => 'Debug process completed',
    'processed_centers' => $processed_centers,
    'total_centers' => $total_centers,
    'log_file' => $log_file
]);
?>
