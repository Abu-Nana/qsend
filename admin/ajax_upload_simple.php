<?php
/**
 * Simple Upload Handler - Alternative Approach
 * This bypasses the complex logic and uses direct file operations
 */

// Start output buffering to catch any errors
ob_start();

// Start session
session_start();

// Set header for JSON response
header('Content-Type: application/json');

// Clear any previous output
ob_clean();

// Simple session check (allow for development)
if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
    error_log("Simple upload: No admin session found - allowing for development");
}

try {
    // Get basic parameters
    $upload_type = isset($_POST['upload_type']) ? $_POST['upload_type'] : 'default';
    $folder_name = isset($_POST['folder_name']) ? trim($_POST['folder_name']) : '';
    
    // Determine target folder
    if ($upload_type === 'makeup' && !empty($folder_name)) {
        $folder_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $folder_name);
        $target_folder = __DIR__ . '/DEASemester/' . $folder_name;
    } else {
        $target_folder = __DIR__ . '/DEASemester';
    }
    
    // Create folder if it doesn't exist
    if (!is_dir($target_folder)) {
        // Use 0777 for upload compatibility (temporary for development)
        mkdir($target_folder, 0777, true);
        chmod($target_folder, 0777);
    }
    
    // Ensure directory is writable
    if (!is_writable($target_folder)) {
        throw new Exception('Target directory is not writable: ' . $target_folder);
    }
    
    $uploaded_count = 0;
    $skipped_files = [];
    $success_files = [];
    
    // Handle ZIP file upload
    if (isset($_FILES['zip_file']) && $_FILES['zip_file']['error'] === UPLOAD_ERR_OK) {
        $zip_file = $_FILES['zip_file']['tmp_name'];
        $zip_name = $_FILES['zip_file']['name'];
        
        // Create unique temp directory
        $temp_dir = __DIR__ . '/temp/extract_' . uniqid();
        mkdir($temp_dir, 0775, true);
        
        // Extract ZIP using system command (more reliable)
        $extract_cmd = "cd " . escapeshellarg($temp_dir) . " && unzip -q " . escapeshellarg($zip_file);
        $extract_result = shell_exec($extract_cmd . " 2>&1");
        
        if ($extract_result === null || strpos($extract_result, 'error') === false) {
            // Process extracted files
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($temp_dir),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $file_path = $file->getRealPath();
                    $file_name = $file->getFilename();
                    
                    // Only process PDF files
                    if (strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) === 'pdf') {
                        // Create safe filename
                        $safe_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $file_name);
                        $destination = $target_folder . '/' . $safe_name;
                        
                        // Check if file already exists
                        if (file_exists($destination)) {
                            $skipped_files[] = $file_name . ' (already exists)';
                            continue;
                        }
                        
                        // Use file_get_contents/file_put_contents (most reliable)
                        $content = file_get_contents($file_path);
                        if ($content !== false && file_put_contents($destination, $content)) {
                            chmod($destination, 0644);
                            $uploaded_count++;
                            $success_files[] = $file_name;
                        } else {
                            $skipped_files[] = $file_name . ' (failed to copy)';
                        }
                    }
                }
            }
        } else {
            throw new Exception('Failed to extract ZIP file: ' . $extract_result);
        }
        
        // Clean up temp directory
        shell_exec("rm -rf " . escapeshellarg($temp_dir));
    }
    
    // Handle individual PDF files
    if (isset($_FILES['pdf_files']) && !empty($_FILES['pdf_files']['name'][0])) {
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
            
            // Create safe filename
            $safe_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $file_name);
            $destination = $target_folder . '/' . $safe_name;
            
            // Check if file already exists
            if (file_exists($destination)) {
                $skipped_files[] = $file_name . ' (already exists)';
                continue;
            }
            
            // Use file_get_contents/file_put_contents
            $content = file_get_contents($tmp_name);
            if ($content !== false && file_put_contents($destination, $content)) {
                chmod($destination, 0644);
                $uploaded_count++;
                $success_files[] = $file_name;
            } else {
                $skipped_files[] = $file_name . ' (failed to copy)';
            }
        }
    }
    
    // Prepare response
    $message = "Successfully uploaded $uploaded_count file(s)";
    if (!empty($success_files)) {
        $message .= " (" . implode(', ', $success_files) . ")";
    }
    if (!empty($skipped_files)) {
        $message .= ". Skipped: " . implode(', ', $skipped_files);
    }
    
    echo json_encode([
        'success' => true,
        'message' => $message,
        'uploaded_count' => $uploaded_count,
        'skipped_count' => count($skipped_files),
        'target_folder' => basename($target_folder),
        'success_files' => $success_files,
        'skipped_files' => $skipped_files
    ]);
    
} catch (Exception $e) {
    // Clear any output and send JSON error
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
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
