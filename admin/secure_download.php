<?php
/**
 * Secure File Download Handler
 * This file provides secure access to DEASemester files
 * Only authenticated admin users can download files
 */

// Start session and check authentication
session_start();

// Check if user is authenticated admin
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    http_response_code(403);
    die('Access Denied: Authentication required');
}

// Get file path from request
$file_path = isset($_GET['file']) ? $_GET['file'] : '';

// Validate file path
if (empty($file_path)) {
    http_response_code(400);
    die('Error: No file specified');
}

// Sanitize file path to prevent directory traversal
$file_path = str_replace(['..', '//', '\\'], '', $file_path);
$file_path = ltrim($file_path, '/');

// Build full path
$full_path = __DIR__ . '/DEASemester/' . $file_path;

// Check if file exists
if (!file_exists($full_path)) {
    http_response_code(404);
    die('Error: File not found');
}

// Check if file is within DEASemester directory
$real_path = realpath($full_path);
$deasemester_path = realpath(__DIR__ . '/DEASemester');

if (strpos($real_path, $deasemester_path) !== 0) {
    http_response_code(403);
    die('Error: Access denied - file outside allowed directory');
}

// Check if file is a PDF
$file_extension = strtolower(pathinfo($full_path, PATHINFO_EXTENSION));
if ($file_extension !== 'pdf') {
    http_response_code(403);
    die('Error: Only PDF files are allowed');
}

// Log the download attempt
$log_entry = date('Y-m-d H:i:s') . " - Admin: " . $_SESSION['admin'] . " downloaded: " . basename($full_path) . "\n";
error_log($log_entry, 3, __DIR__ . '/logs/secure_downloads.log');

// Set headers for file download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($full_path) . '"');
header('Content-Length: ' . filesize($full_path));
header('Cache-Control: no-cache, must-revalidate');
header('Expires: 0');

// Output file
readfile($full_path);
exit;
?>
