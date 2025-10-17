<?php
/**
 * AJAX Handler for Deleting Question Files
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

// Set header for JSON response
header('Content-Type: application/json');

try {
    // Get file path from POST
    $file_path = isset($_POST['file']) ? $_POST['file'] : '';
    
    if (empty($file_path)) {
        throw new Exception('No file specified');
    }
    
    // Sanitize file path to prevent directory traversal
    $file_path = str_replace(['..', '\\'], '', $file_path);
    
    // Build full path
    $full_path = __DIR__ . '/' . $file_path;
    
    // Check if file exists
    if (!file_exists($full_path)) {
        throw new Exception('File not found');
    }
    
    // Check if it's a PDF file
    if (strtolower(pathinfo($full_path, PATHINFO_EXTENSION)) !== 'pdf') {
        throw new Exception('Only PDF files can be deleted');
    }
    
    // Delete the file
    if (!unlink($full_path)) {
        throw new Exception('Failed to delete file');
    }
    
    // Log the deletion
    $username = $_SESSION['admin'];
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    $log_message = date('Y-m-d H:i:s') . " - User: " . ($user ? $user['username'] : 'Unknown') . 
                   " deleted file: " . basename($file_path) . "\n";
    file_put_contents(__DIR__ . '/logs/question_deletes.log', $log_message, FILE_APPEND | LOCK_EX);
    
    ob_clean();
    echo json_encode([
        'success' => true,
        'message' => 'File deleted successfully'
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

