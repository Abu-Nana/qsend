<?php
/**
 * Import Data Handler - Debug Version
 * 
 * @package QSEND
 * @version 2.0
 */

// Start output buffering to prevent header issues
ob_start();

// Disable display errors in production (errors are logged)
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED);

// Start session
session_start();

// Include database configuration
require_once __DIR__ . '/../config/database.php';

// Check if form was submitted
if(!isset($_POST['importSubmit'])){
    $_SESSION['error'] = 'Form was not submitted properly. Please try again.';
    ob_end_clean();
    header('location: rawslip.php?status=err');
    exit;
}

// Validate file upload
if(!isset($_FILES['file'])) {
    $_SESSION['error'] = 'No file was uploaded.';
    ob_end_clean();
    header('location: rawslip.php?status=err');
    exit;
}

if($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'File upload error: ' . $_FILES['file']['error'];
    ob_end_clean();
    header('location: rawslip.php?status=err');
    exit;
}

// Allowed mime types
$csvMimes = array(
    'text/x-comma-separated-values', 
    'text/comma-separated-values', 
    'application/octet-stream', 
    'application/vnd.ms-excel', 
    'application/x-csv', 
    'text/x-csv', 
    'text/csv', 
    'application/csv', 
    'application/excel', 
    'application/vnd.msexcel', 
    'text/plain'
);

// Validate file type
if(!in_array($_FILES['file']['type'], $csvMimes)) {
    $_SESSION['error'] = 'Invalid file type: ' . $_FILES['file']['type'] . '. Please upload a CSV file.';
    ob_end_clean();
    header('location: rawslip.php?status=invalid_file');
    exit;
}

// Check file size (10MB limit)
if($_FILES['file']['size'] > 10 * 1024 * 1024) {
    $_SESSION['error'] = 'File size exceeds 10MB limit.';
    ob_end_clean();
    header('location: rawslip.php?status=err');
    exit;
}

try {
    // Open uploaded CSV file
    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
    
    if(!$csvFile) {
        throw new Exception('Could not open CSV file');
    }
    
    // Read header row
    $headers = fgetcsv($csvFile, 0, ',', '"', '\\');
    
    // Initialize counters
    $processedRecords = 0;
    $errorRecords = 0;
    $successRecords = 0;
    $updatedRecords = 0;
    $insertedRecords = 0;
    
    // Process each row
    while(($line = fgetcsv($csvFile, 0, ',', '"', '\\')) !== FALSE) {
        
        // Skip empty rows
        if(empty($line[0])) {
            continue;
        }
        
        try {
            // Get row data
            $matric = isset($line[0]) ? trim($line[0]) : '';
            $studycentre = isset($line[1]) ? trim($line[1]) : '';
            $centrecode = isset($line[2]) ? trim($line[2]) : '';
            $course = isset($line[3]) ? trim($line[3]) : '';
            $examday = isset($line[4]) ? trim($line[4]) : '';
            $examsession = isset($line[5]) ? trim($line[5]) : '';
            $examdate = isset($line[6]) ? trim($line[6]) : '';
            
            // Validate required fields
            if(empty($matric)) {
                $errorRecords++;
                continue;
            }
            
            // Check if record exists
            $checkQuery = "SELECT id FROM student_registrations WHERE matric_number = ?";
            $checkStmt = $conn->prepare($checkQuery);
            
            if(!$checkStmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $checkStmt->bind_param("s", $matric);
            $checkStmt->execute();
            $existingRecord = $checkStmt->get_result();
            
            if($existingRecord->num_rows > 0) {
                // Update existing
                $updateQuery = "UPDATE student_registrations SET 
                    study_center = ?, 
                    study_center_code = ?, 
                    course = ?, 
                    exam_day = ?, 
                    exam_session = ?, 
                    exa_date = ?
                    WHERE matric_number = ?";
                
                $updateStmt = $conn->prepare($updateQuery);
                
                if(!$updateStmt) {
                    throw new Exception("Update prepare failed: " . $conn->error);
                }
                
                $updateStmt->bind_param("sssssss", 
                    $studycentre, $centrecode, $course, 
                    $examday, $examsession, $examdate, $matric
                );
                
                if($updateStmt->execute()) {
                    $successRecords++;
                    $updatedRecords++;
                } else {
                    $errorRecords++;
                }
                $updateStmt->close();
            } else {
                // Insert new
                $insertQuery = "INSERT INTO student_registrations 
                    (matric_number, study_center, study_center_code, course, 
                     exam_day, exam_session, exa_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $insertStmt = $conn->prepare($insertQuery);
                
                if(!$insertStmt) {
                    throw new Exception("Insert prepare failed: " . $conn->error);
                }
                
                $insertStmt->bind_param("sssssss", 
                    $matric, $studycentre, $centrecode, $course, 
                    $examday, $examsession, $examdate
                );
                
                if($insertStmt->execute()) {
                    $successRecords++;
                    $insertedRecords++;
                    error_log("Inserted: $matric");
                } else {
                    $errorRecords++;
                    error_log("Insert error: " . $insertStmt->error);
                }
                $insertStmt->close();
            }
            
            $checkStmt->close();
            $processedRecords++;
            
        } catch (Exception $e) {
            $errorRecords++;
        }
    }
    
    fclose($csvFile);
    
    // Set success message
    $_SESSION['success'] = "Import completed! Processed: $processedRecords | Inserted: $insertedRecords | Updated: $updatedRecords | Errors: $errorRecords";
    
    ob_end_clean();
    header('location: rawslip.php?status=succ');
    exit;
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Import failed: ' . $e->getMessage();
    ob_end_clean();
    header('location: rawslip.php?status=err');
    exit;
}
?>