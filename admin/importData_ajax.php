<?php
/**
 * AJAX Import Data Handler
 * Returns JSON progress updates
 * 
 * @package QSEND
 * @version 2.0
 */

header('Content-Type: application/json');

// Start output buffering
ob_start();

// Disable display errors
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED);

// Start session
session_start();

// Include database configuration
require_once __DIR__ . '/../config/database.php';

// Response array
$response = [
    'success' => false,
    'message' => '',
    'data' => [
        'total' => 0,
        'processed' => 0,
        'inserted' => 0,
        'updated' => 0,
        'errors' => 0
    ]
];

// Check if form was submitted
if(!isset($_POST['importSubmit'])){
    $response['message'] = 'Form was not submitted properly.';
    ob_end_clean();
    echo json_encode($response);
    exit;
}

// Validate file upload
if(!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $response['message'] = 'File upload error.';
    ob_end_clean();
    echo json_encode($response);
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
    $response['message'] = 'Invalid file type. Please upload a CSV file.';
    ob_end_clean();
    echo json_encode($response);
    exit;
}

try {
    // Open CSV file
    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
    
    if(!$csvFile) {
        throw new Exception('Could not open CSV file');
    }
    
    // Skip header row
    $headers = fgetcsv($csvFile, 0, ',', '"', '\\');
    
    // Initialize counters
    $processedRecords = 0;
    $errorRecords = 0;
    $insertedRecords = 0;
    $updatedRecords = 0;
    
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
            $checkStmt = $conn->prepare("SELECT id FROM student_registrations WHERE matric_number = ?");
            $checkStmt->bind_param("s", $matric);
            $checkStmt->execute();
            $existingRecord = $checkStmt->get_result();
            
            if($existingRecord->num_rows > 0) {
                // Update existing
                $updateStmt = $conn->prepare("UPDATE student_registrations SET 
                    study_center = ?, study_center_code = ?, course = ?, 
                    exam_day = ?, exam_session = ?, exa_date = ?
                    WHERE matric_number = ?");
                
                $updateStmt->bind_param("sssssss", 
                    $studycentre, $centrecode, $course, 
                    $examday, $examsession, $examdate, $matric
                );
                
                if($updateStmt->execute()) {
                    $updatedRecords++;
                } else {
                    $errorRecords++;
                }
                $updateStmt->close();
            } else {
                // Insert new
                $insertStmt = $conn->prepare("INSERT INTO student_registrations 
                    (matric_number, study_center, study_center_code, course, 
                     exam_day, exam_session, exa_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                
                $insertStmt->bind_param("sssssss", 
                    $matric, $studycentre, $centrecode, $course, 
                    $examday, $examsession, $examdate
                );
                
                if($insertStmt->execute()) {
                    $insertedRecords++;
                } else {
                    $errorRecords++;
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
    
    // Success response
    $response['success'] = true;
    $response['message'] = "Import completed successfully!";
    $response['data'] = [
        'total' => $processedRecords,
        'processed' => $processedRecords,
        'inserted' => $insertedRecords,
        'updated' => $updatedRecords,
        'errors' => $errorRecords
    ];
    
} catch (Exception $e) {
    $response['message'] = 'Import failed: ' . $e->getMessage();
}

ob_end_clean();
echo json_encode($response);
?>

