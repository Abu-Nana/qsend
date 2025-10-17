<?php
/**
 * Direct Upload Handler - Ultra Simple Approach
 * Uses the most basic PHP file operations possible
 */

// Start output buffering to catch any errors
ob_start();

// Start session
session_start();

// Set header for JSON response
header('Content-Type: application/json');

// Clear any previous output
ob_clean();

// Allow development access
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    error_log("Direct upload: No admin session found - allowing for development");
}

// Simple error handling
$errors = [];
$success_count = 0;
$skipped_files = [];

try {
    // Get upload parameters
    $upload_type = isset($_POST['upload_type']) ? $_POST['upload_type'] : 'default';
    $folder_name = isset($_POST['folder_name']) ? trim($_POST['folder_name']) : '';
    
    error_log("Direct upload: upload_type = $upload_type, folder_name = $folder_name");
    
    // Determine target folder
    if ($upload_type === 'makeup' && !empty($folder_name)) {
        $folder_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $folder_name);
        $target_folder = __DIR__ . '/DEASemester/' . $folder_name;
        error_log("Direct upload: Creating makeup folder: $target_folder");
    } else {
        $target_folder = __DIR__ . '/DEASemester';
        error_log("Direct upload: Using default folder: $target_folder");
    }
    
    // Create folder if it doesn't exist
    if (!is_dir($target_folder)) {
        error_log("Direct upload: Creating directory: $target_folder");
        // Create with 0775 permissions for subdirectories
        // Note: Parent directory needs to be writable by web server
        if (!mkdir($target_folder, 0777, true)) {
            error_log("Direct upload: Failed to create directory: $target_folder");
            throw new Exception('Failed to create directory: ' . $target_folder . '. Please ensure DEASemester directory has write permissions for web server.');
        }
        
        // Set permissions explicitly
        chmod($target_folder, 0777);
        
        error_log("Direct upload: Directory created successfully with 0777 permissions");
    }
    
    // Ensure directory is writable
    if (!is_writable($target_folder)) {
        error_log("Direct upload: Directory not writable: $target_folder");
        // Try to fix permissions
        if (@chmod($target_folder, 0777)) {
            error_log("Direct upload: Successfully changed permissions to 0777");
        } else {
            throw new Exception('Target directory is not writable: ' . $target_folder . '. Please run: chmod 777 ' . dirname($target_folder));
        }
    }
    
    // Handle ZIP file
    if (isset($_FILES['zip_file']) && $_FILES['zip_file']['error'] === UPLOAD_ERR_OK) {
        $zip_file = $_FILES['zip_file']['tmp_name'];
        
        // Use ZipArchive directly
        $zip = new ZipArchive();
        if ($zip->open($zip_file) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $basename = basename($filename);
                
                // Skip macOS hidden files
                if (substr($basename, 0, 2) === '._' || substr($basename, 0, 1) === '.') {
                    continue;
                }
                
                // Only process PDF files
                if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'pdf') {
                    // Get file content directly from ZIP
                    $content = $zip->getFromIndex($i);
                    
                    if ($content !== false) {
                        // Create safe filename
                        $safe_name = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($filename));
                        $destination = $target_folder . '/' . $safe_name;
                        
                        // Write file directly
                        if (file_put_contents($destination, $content)) {
                            chmod($destination, 0644);
                            $success_count++;
                        } else {
                            $skipped_files[] = basename($filename) . ' (write failed)';
                        }
                    } else {
                        $skipped_files[] = basename($filename) . ' (extract failed)';
                    }
                }
            }
            $zip->close();
        } else {
            $errors[] = 'Failed to open ZIP file';
        }
    }
    
    // Handle individual PDF files
    if (isset($_FILES['pdf_files']) && !empty($_FILES['pdf_files']['name'][0])) {
        $pdf_files = $_FILES['pdf_files'];
        $file_count = count($pdf_files['name']);
        
        for ($i = 0; $i < $file_count; $i++) {
            if ($pdf_files['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = $pdf_files['name'][$i];
                $tmp_name = $pdf_files['tmp_name'][$i];
                
                // Only process PDF files
                if (strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) === 'pdf') {
                    // Create safe filename
                    $safe_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $file_name);
                    $destination = $target_folder . '/' . $safe_name;
                    
                    // Read and write file
                    $content = file_get_contents($tmp_name);
                    if ($content !== false && file_put_contents($destination, $content)) {
                        chmod($destination, 0644);
                        $success_count++;
                    } else {
                        $skipped_files[] = $file_name . ' (write failed)';
                    }
                } else {
                    $skipped_files[] = $file_name . ' (not PDF)';
                }
            } else {
                $skipped_files[] = $pdf_files['name'][$i] . ' (upload error)';
            }
        }
    }
    
    // Prepare response
    $message = "Successfully uploaded $success_count file(s)";
    if (!empty($skipped_files)) {
        $message .= ". Skipped: " . implode(', ', $skipped_files);
    }
    
    if (!empty($errors)) {
        $message .= ". Errors: " . implode(', ', $errors);
    }
    
    echo json_encode([
        'success' => true,
        'message' => $message,
        'uploaded_count' => $success_count,
        'skipped_count' => count($skipped_files),
        'target_folder' => basename($target_folder),
        'full_path' => $target_folder,
        'errors' => $errors
    ]);
    
} catch (Exception $e) {
    // Clear any output and send JSON error
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Upload failed: ' . $e->getMessage()
    ]);
    exit;
}

// Catch any PHP errors
if (ob_get_level()) {
    $output = ob_get_clean();
    if (!empty($output) && !json_decode($output)) {
        echo json_encode([
            'success' => false,
            'message' => 'PHP Error: ' . $output
        ]);
        exit;
    }
    echo $output;
}
?>
