<?php
/**
 * AJAX Handler for Uploading Question PDFs
 * Supports both ZIP extraction and individual PDF uploads
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
    error_log("ajax_upload_questions.php: No admin session found - allowing for development");
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

// Increase limits for large files
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);
ini_set('post_max_size', '500M');
ini_set('upload_max_filesize', '500M');

try {
    // Get POST data
    $upload_type = isset($_POST['upload_type']) ? $_POST['upload_type'] : 'default';
    $upload_method = isset($_POST['upload_method']) ? $_POST['upload_method'] : 'zip';
    $folder_name = isset($_POST['folder_name']) ? trim($_POST['folder_name']) : '';
    
    // Debug logging
    error_log("Upload debug - Type: $upload_type, Method: $upload_method, Folder: $folder_name");
    error_log("Upload debug - FILES: " . print_r($_FILES, true));
    
    // Check if we have the right files for the method
    if ($upload_method === 'zip' && !isset($_FILES['zip_file'])) {
        error_log("ERROR: ZIP method selected but no zip_file in FILES");
    }
    if ($upload_method === 'pdf' && !isset($_FILES['pdf_files'])) {
        error_log("ERROR: PDF method selected but no pdf_files in FILES");
    }
    
    // Determine target folder
    if ($upload_type === 'makeup') {
        if (empty($folder_name)) {
            throw new Exception('Folder name is required for makeup exam');
        }
        
        // Sanitize folder name
        $folder_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $folder_name);
        $target_folder = __DIR__ . '/DEASemester/' . $folder_name;
    } else {
        $target_folder = __DIR__ . '/DEASemester';
    }
    
    // Create folder if it doesn't exist
    if (!is_dir($target_folder)) {
        // Use 0777 for upload compatibility (temporary for development)
        if (!mkdir($target_folder, 0777, true)) {
            throw new Exception('Failed to create directory: ' . $target_folder);
        }
        chmod($target_folder, 0777);
    }
    
    $uploaded_count = 0;
    $skipped_files = [];
    
    error_log("Upload handler: Starting with uploaded_count = $uploaded_count");
    
    // Handle ZIP upload
    if ($upload_method === 'zip') {
        error_log("Processing ZIP upload method");
        if (!isset($_FILES['zip_file']) || $_FILES['zip_file']['error'] !== UPLOAD_ERR_OK) {
            error_log("No ZIP file found in upload");
            throw new Exception('No ZIP file uploaded or upload error occurred');
        }
        
        $zip_file = $_FILES['zip_file']['tmp_name'];
        $zip = new ZipArchive();
        
        if ($zip->open($zip_file) === TRUE) {
            // Create temporary extraction folder
            $temp_folder = __DIR__ . '/temp/extract_' . time();
            if (!is_dir($temp_folder)) {
                mkdir($temp_folder, 0775, true);
            }
            
            // Extract ZIP
            $zip->extractTo($temp_folder);
            $zip->close();
            
            // Process extracted files
            $files = scandir($temp_folder);
            error_log("ZIP extraction: Found " . count($files) . " files in temp folder");
            
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $file_path = $temp_folder . '/' . $file;
                error_log("ZIP extraction: Processing file: " . $file);
                
                // Check if it's a PDF file
                if (is_file($file_path) && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf') {
                    // Sanitize filename to avoid issues with special characters
                    $safe_filename = preg_replace('/[^A-Za-z0-9._-]/', '_', $file);
                    $destination = $target_folder . '/' . $safe_filename;
                    error_log("ZIP extraction: PDF found - " . $file . " -> " . $destination . " (sanitized: " . $safe_filename . ")");
                    
                    // Check if file already exists
                    if (file_exists($destination)) {
                        $skipped_files[] = $file . ' (already exists)';
                        error_log("ZIP extraction: File already exists - " . $file);
                        continue;
                    }
                    
                    // Copy file to target folder
                    error_log("ZIP extraction: Attempting copy from: " . $file_path . " to: " . $destination);
                    error_log("ZIP extraction: Source file exists: " . (file_exists($file_path) ? 'YES' : 'NO'));
                    error_log("ZIP extraction: Source file readable: " . (is_readable($file_path) ? 'YES' : 'NO'));
                    error_log("ZIP extraction: Destination directory exists: " . (is_dir(dirname($destination)) ? 'YES' : 'NO'));
                    error_log("ZIP extraction: Destination directory writable: " . (is_writable(dirname($destination)) ? 'YES' : 'NO'));
                    
                    if (copy($file_path, $destination)) {
                        chmod($destination, 0644);
                        $uploaded_count++;
                        error_log("ZIP extraction: Successfully copied - " . $file . " (uploaded_count now: $uploaded_count)");
                    } else {
                        $skipped_files[] = $file . ' (failed to copy)';
                        error_log("ZIP extraction: Failed to copy - " . $file);
                        error_log("ZIP extraction: copy() returned false");
                        
                        // Try alternative method
                        if (file_get_contents($file_path) !== false) {
                            $content = file_get_contents($file_path);
                            if (file_put_contents($destination, $content)) {
                                chmod($destination, 0644);
                                $uploaded_count++;
                                error_log("ZIP extraction: Successfully copied using file_get_contents/file_put_contents - " . $file . " (uploaded_count now: $uploaded_count)");
                            } else {
                                error_log("ZIP extraction: file_put_contents also failed - " . $file);
                            }
                        } else {
                            error_log("ZIP extraction: file_get_contents failed - " . $file);
                        }
                    }
                } else {
                    error_log("ZIP extraction: Not a PDF file - " . $file . " (extension: " . pathinfo($file, PATHINFO_EXTENSION) . ")");
                }
            }
            
            // Clean up temp folder
            deleteDirectory($temp_folder);
            
        } else {
            throw new Exception('Failed to open ZIP file');
        }
        
    } 
    // Handle individual PDF uploads
    else if ($upload_method === 'pdf') {
        error_log("Processing PDF upload method");
        if (!isset($_FILES['pdf_files']) || empty($_FILES['pdf_files']['name'][0])) {
            error_log("No PDF files found in upload");
            throw new Exception('No PDF files uploaded');
        }
        
        $pdf_files = $_FILES['pdf_files'];
        $file_count = count($pdf_files['name']);
        
        for ($i = 0; $i < $file_count; $i++) {
            if ($pdf_files['error'][$i] !== UPLOAD_ERR_OK) {
                $skipped_files[] = $pdf_files['name'][$i] . ' (upload error)';
                continue;
            }
            
            $file_name = $pdf_files['name'][$i];
            $tmp_name = $pdf_files['tmp_name'][$i];
            
            // Validate PDF
            if (strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) !== 'pdf') {
                $skipped_files[] = $file_name . ' (not a PDF)';
                continue;
            }
            
            // Sanitize filename to avoid issues with special characters
            $safe_filename = preg_replace('/[^A-Za-z0-9._-]/', '_', $file_name);
            $destination = $target_folder . '/' . $safe_filename;
            error_log("PDF upload: Original filename: " . $file_name . " -> Sanitized: " . $safe_filename);
            
            // Check if file already exists
            if (file_exists($destination)) {
                $skipped_files[] = $file_name . ' (already exists)';
                continue;
            }
            
            // Move uploaded file
            error_log("PDF upload: Attempting to move file from $tmp_name to $destination");
            error_log("PDF upload: Source file exists: " . (file_exists($tmp_name) ? 'YES' : 'NO'));
            error_log("PDF upload: Source file size: " . (file_exists($tmp_name) ? filesize($tmp_name) : 'N/A'));
            error_log("PDF upload: Destination directory: " . dirname($destination));
            error_log("PDF upload: Destination directory exists: " . (is_dir(dirname($destination)) ? 'YES' : 'NO'));
            error_log("PDF upload: Destination directory writable: " . (is_writable(dirname($destination)) ? 'YES' : 'NO'));
            
            if (move_uploaded_file($tmp_name, $destination)) {
                chmod($destination, 0644);
                $uploaded_count++;
                error_log("PDF upload: File moved successfully: $file_name");
            } else {
                $skipped_files[] = $file_name . ' (failed to move)';
                error_log("PDF upload: Failed to move file: $file_name");
                error_log("PDF upload: move_uploaded_file() returned false");
                
                // Try alternative method
                error_log("PDF upload: Trying copy() as fallback");
                if (copy($tmp_name, $destination)) {
                    unlink($tmp_name);
                    chmod($destination, 0644);
                    $uploaded_count++;
                    error_log("PDF upload: File copied successfully using copy(): $file_name");
                } else {
                    error_log("PDF upload: copy() also failed, trying file_get_contents/file_put_contents");
                    
                    // Try file_get_contents/file_put_contents
                    if (file_get_contents($tmp_name) !== false) {
                        $content = file_get_contents($tmp_name);
                        if (file_put_contents($destination, $content)) {
                            unlink($tmp_name);
                            chmod($destination, 0644);
                            $uploaded_count++;
                            error_log("PDF upload: File copied successfully using file_get_contents/file_put_contents: $file_name");
                        } else {
                            error_log("PDF upload: file_put_contents also failed for: $file_name");
                        }
                    } else {
                        error_log("PDF upload: file_get_contents failed for: $file_name");
                    }
                }
            }
        }
    }
    
    // Prepare response message
    error_log("Upload handler: Final uploaded_count = $uploaded_count");
    error_log("Upload handler: Final skipped_files count = " . count($skipped_files));
    error_log("Upload handler: Skipped files: " . implode(', ', $skipped_files));
    
    $message = "Successfully uploaded $uploaded_count file(s)";
    if (!empty($skipped_files)) {
        $message .= ". Skipped: " . implode(', ', $skipped_files);
    }
    
    // Log the upload
    $username = isset($_SESSION['admin']) ? $_SESSION['admin'] : 'Anonymous';
    $log_message = date('Y-m-d H:i:s') . " - User: " . $username . 
                   " uploaded $uploaded_count files to folder: " . basename($target_folder) . "\n";
    file_put_contents(__DIR__ . '/logs/question_uploads.log', $log_message, FILE_APPEND | LOCK_EX);
    
    ob_clean();
    echo json_encode([
        'success' => true,
        'message' => $message,
        'uploaded_count' => $uploaded_count,
        'skipped_count' => count($skipped_files),
        'target_folder' => basename($target_folder)
    ]);
    
} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;

/**
 * Delete a directory and all its contents
 */
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}
?>

