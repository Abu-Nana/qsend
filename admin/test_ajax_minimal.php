<?php
/**
 * Minimal AJAX test to isolate the issue
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
    echo "=== MINIMAL AJAX TEST ===\n";
    
    // Include database connection
    require_once __DIR__ . '/../config/database.php';
    $conn = Database::getPDO();
    echo "✅ Database connection OK\n";

    // Check if user is logged in
    if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Unauthorized access'
        ]);
        exit;
    }
    echo "✅ Session check OK\n";

    // Get user details
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
    echo "✅ User lookup OK\n";

    // Get POST data
    $exam_session = isset($_POST['exam_session']) ? $_POST['exam_session'] : '';
    $day = isset($_POST['exam_day']) ? $_POST['exam_day'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';

    echo "POST Data - Session: '$exam_session', Day: '$day', Subject: '$subject'\n";

    // Validate required fields
    if (empty($exam_session) || empty($day) || empty($subject)) {
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Please fill in all required fields'
        ]);
        exit;
    }
    echo "✅ Validation OK\n";

    // Test the query
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

    // Simple success response
    ob_clean();
    echo json_encode([
        'success' => true,
        'message' => 'Test completed successfully',
        'data_count' => count($query_data),
        'sample_data' => $query_data[0] ?? null
    ]);

} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Exception: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} catch (Error $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Fatal Error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>
