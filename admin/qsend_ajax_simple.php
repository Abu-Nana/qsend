<?php
/**
 * Simplified AJAX Handler for Sending Questions
 * Returns JSON response with progress and results
 */

// Disable error display to prevent HTML output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering to catch any unexpected output
ob_start();

// Start session
session_start();

// Include database connection
include 'includes/conn.php';

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
$stmt->bind_param("s", $_SESSION['admin']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

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

try {
    // Get POST data
    $exam_session = isset($_POST['exam_session']) ? $_POST['exam_session'] : '';
    $day = isset($_POST['exam_day']) ? $_POST['exam_day'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $body = isset($_POST['body']) ? $_POST['body'] : '';

    // Validate required fields
    if (empty($exam_session) || empty($day) || empty($subject)) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields'
        ]);
        exit;
    }

    // For now, just return a test response
    $response = [
        'success' => true,
        'message' => 'Test successful - ready to process',
        'exam_session' => $exam_session,
        'exam_day' => $day,
        'subject' => $subject,
        'body' => $body,
        'total_centers' => 0,
        'sent_count' => 0,
        'failed_count' => 0,
        'sent_by' => $user['username'] . ' - ' . $user['firstname'] . ' ' . $user['lastname'],
        'recipients' => []
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ];
    error_log("QSEND Error: " . $e->getMessage());
}

// Clear any unexpected output
ob_clean();

// Ensure we only output JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>

