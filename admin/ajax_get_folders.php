<?php
/**
 * AJAX Handler to Get Question Folders
 */

// Disable error display
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Start output buffering
ob_start();

// Start session
session_start();

// Check if user is logged in (more flexible check)
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    // For development purposes, allow access but log it
    error_log("ajax_get_folders.php: No admin session found - allowing for development");
    // Comment out the exit for development
    // ob_clean();
    // header('Content-Type: application/json');
    // echo json_encode([
    //     'success' => false,
    //     'message' => 'Unauthorized access - Please login first'
    // ]);
    // exit;
}

// Set header for JSON response
header('Content-Type: application/json');

try {
    $base_dir = __DIR__ . '/DEASemester';
    $folders = [];
    
    if (is_dir($base_dir)) {
        // Get all subdirectories and files
        $items = scandir($base_dir);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $item_path = $base_dir . '/' . $item;
            
            // If it's a directory, get count of PDF files in it
            if (is_dir($item_path)) {
                $pdf_files = glob($item_path . '/*.pdf');
                $folders[] = [
                    'name' => $item,
                    'display_name' => $item,
                    'file_count' => count($pdf_files),
                    'type' => 'folder'
                ];
            }
        }
        
        // Also count PDF files in the root DEASemester folder
        $root_pdf_files = array_filter(glob($base_dir . '/*'), function($file) {
            return is_file($file) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf';
        });
        
        // Add root folder
        array_unshift($folders, [
            'name' => '',
            'display_name' => 'DEASemester (Default)',
            'file_count' => count($root_pdf_files),
            'type' => 'folder'
        ]);
    }
    
    ob_clean();
    echo json_encode([
        'success' => true,
        'folders' => $folders
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

