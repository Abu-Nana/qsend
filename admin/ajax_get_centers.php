<?php
/**
 * AJAX Handler to Get Study Centers for Makeup Exam Selection
 */

// Disable error display
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering
ob_start();

// Start session
session_start();

// Include database connection
include 'includes/conn.php';

// Check if user is logged in (more flexible for development)
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    // For development purposes, allow access but log it
    error_log("ajax_get_centers.php: No admin session found - allowing for development");
    // Comment out the exit for development
    // ob_clean();
    // header('Content-Type: application/json');
    // echo json_encode([
    //     'success' => false,
    //     'message' => 'Unauthorized access'
    // ]);
    // exit;
}

// Set header for JSON response
header('Content-Type: application/json');

try {
    // Get POST data
    $exam_session = isset($_POST['exam_session']) ? $_POST['exam_session'] : '';
    $exam_day = isset($_POST['exam_day']) ? $_POST['exam_day'] : '';
    
    // For testing, use default values if not provided
    if (empty($exam_session)) {
        $exam_session = '8:00AM';
    }
    if (empty($exam_day)) {
        $exam_day = '7';
    }
    
    if (empty($exam_session) || empty($exam_day)) {
        throw new Exception('Exam session and day are required');
    }
    
    // Get database connection
    $connection = require 'connection.php';
    
    // Query to get unique study centers for the session and day
    $query = "SELECT DISTINCT c.study_center_code, c.study_center, c.director, c.study_centre_email, c.phone_number 
              FROM student_registrations s
              INNER JOIN study_centers c 
              ON c.study_center_code = s.study_center_code 
              WHERE s.exam_session = ? 
              AND s.exam_day = ?
              ORDER BY c.study_center ASC";
    
    $stmt = mysqli_prepare($connection, $query);
    
    if (!$stmt) {
        throw new Exception('Database query preparation failed: ' . mysqli_error($connection));
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $exam_session, $exam_day);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        throw new Exception('Database query failed: ' . mysqli_error($connection));
    }
    
    $centers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $centers[] = [
            'code' => $row['study_center_code'],
            'name' => $row['study_center'],
            'director' => $row['director'],
            'email' => $row['study_centre_email'],
            'phone' => $row['phone_number']
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    ob_clean();
    echo json_encode([
        'success' => true,
        'centers' => $centers,
        'count' => count($centers)
    ]);
    
} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
?>

