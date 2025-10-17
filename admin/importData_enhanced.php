<?php
/**
 * Enhanced Import Data Handler
 * 
 * Handles CSV import with progress tracking and better error handling
 * 
 * @package QSEND
 * @version 2.0
 */

// Include database configuration
require_once __DIR__ . '/../config/database.php';

// Start session for progress tracking
session_start();

if(isset($_POST['importSubmit'])){
    
    // Validate file upload
    if(!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
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
        header('location: rawslip.php?status=invalid_file');
        exit;
    }
    
    // Check file size (10MB limit)
    if($_FILES['file']['size'] > 10 * 1024 * 1024) {
        header('location: rawslip.php?status=err');
        exit;
    }
    
    try {
        // Open uploaded CSV file
        $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
        
        if(!$csvFile) {
            throw new Exception('Could not open CSV file');
        }
        
        // Skip header row
        fgetcsv($csvFile);
        
        // Initialize counters
        $totalRecords = 0;
        $processedRecords = 0;
        $errorRecords = 0;
        $successRecords = 0;
        
        // Count total records first
        $tempFile = fopen($_FILES['file']['tmp_name'], 'r');
        fgetcsv($tempFile); // Skip header
        while(fgetcsv($tempFile) !== FALSE) {
            $totalRecords++;
        }
        fclose($tempFile);
        
        // Process each row
        while(($line = fgetcsv($csvFile)) !== FALSE) {
            $totalRecords++;
            
            try {
                // Validate required fields
                if(empty($line[0]) || empty($line[1]) || empty($line[2])) {
                    $errorRecords++;
                    continue;
                }
                
                // Get row data with proper validation
                $matric = trim($line[0]);
                $studycentre = trim($line[1]);
                $centrecode = trim($line[2]);
                $course = trim($line[3]);
                $examday = trim($line[4]);
                $examsession = trim($line[5]);
                $examdate = trim($line[6]);
                
                // Check if record already exists
                $checkQuery = "SELECT id FROM student_registrations WHERE matric_number = ?";
                $checkStmt = $conn->prepare($checkQuery);
                $checkStmt->bind_param("s", $matric);
                $checkStmt->execute();
                $existingRecord = $checkStmt->get_result();
                
                if($existingRecord->num_rows > 0) {
                    // Update existing record
                    $updateQuery = "UPDATE student_registrations SET 
                        study_center = ?, 
                        study_center_code = ?, 
                        course = ?, 
                        exam_day = ?, 
                        exam_session = ?, 
                        exam_date = ?,
                        updated_at = NOW()
                        WHERE matric_number = ?";
                    
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("sssssss", 
                        $studycentre, $centrecode, $course, 
                        $examday, $examsession, $examdate, $matric
                    );
                    
                    if($updateStmt->execute()) {
                        $successRecords++;
                    } else {
                        $errorRecords++;
                    }
                    $updateStmt->close();
                } else {
                    // Insert new record
                    $insertQuery = "INSERT INTO student_registrations 
                        (matric_number, study_center, study_center_code, course, 
                         exam_day, exam_session, exam_date, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
                    
                    $insertStmt = $conn->prepare($insertQuery);
                    $insertStmt->bind_param("sssssss", 
                        $matric, $studycentre, $centrecode, $course, 
                        $examday, $examsession, $examdate
                    );
                    
                    if($insertStmt->execute()) {
                        $successRecords++;
                    } else {
                        $errorRecords++;
                    }
                    $insertStmt->close();
                }
                
                $checkStmt->close();
                $processedRecords++;
                
            } catch (Exception $e) {
                $errorRecords++;
                error_log("Import error: " . $e->getMessage());
            }
        }
        
        fclose($csvFile);
        
        // Set success message
        $_SESSION['success'] = "Import completed successfully! Processed: $processedRecords, Success: $successRecords, Errors: $errorRecords";
        
        // Redirect to success page
        header('location: rawslip.php?status=succ');
        exit;
        
    } catch (Exception $e) {
        error_log("Import process error: " . $e->getMessage());
        header('location: rawslip.php?status=err');
        exit;
    }
} else {
    header('location: rawslip.php');
    exit;
}
?>

