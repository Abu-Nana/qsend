<?php
/**
 * File Download Handler
 */

// Start session
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    die('Unauthorized access');
}

// Get file path from GET
$file_path = isset($_GET['file']) ? $_GET['file'] : '';

if (empty($file_path)) {
    die('No file specified');
}

// Sanitize file path to prevent directory traversal
$file_path = str_replace(['..', '\\'], '', $file_path);

// Build full path
$full_path = __DIR__ . '/' . $file_path;

// Check if file exists
if (!file_exists($full_path)) {
    die('File not found');
}

// Check if it's a PDF file
if (strtolower(pathinfo($full_path, PATHINFO_EXTENSION)) !== 'pdf') {
    die('Invalid file type');
}

// Get file name
$file_name = basename($full_path);

// Set headers for download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Content-Length: ' . filesize($full_path));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Clear output buffer
ob_clean();
flush();

// Read file
readfile($full_path);
exit;
?>

