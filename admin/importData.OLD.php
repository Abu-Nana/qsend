<?php
// Load the database configuration file
// Database configuration
$dbHost     = "localhost";
$dbUsername = "unz9crd0fspuy";
$dbPassword = "kxpqibmc0hpl";
$dbName     = "db3ucjvbbuaxbu";
//	$conn = mysqli_connect("localhost", "unz9crd0fspuy", "kxpqibmc0hpl", "db3ucjvbbuaxbu");
// Create database connection
 $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
               $matric   = $line[0];
                $studycentre   = $line[1];
                $centrecode   = $line[2];
                 $course   = $line[3];
                $examday  = $line[4];
                $examsession = $line[5];
                $examdate   = $line[6];
                
                // Check whether member already exists in the database with the same email
                $prevQuery = "SELECT id FROM student_registrations WHERE ID = '".$line[1]."'";
                $prevResult = $db->query($prevQuery);
                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $db->query("UPDATE student_registrations SET matric_number = '".$matric."', study_center = '".$studycentre."', study_center_code = '".$centrecode."' WHERE matric_number = '".$matric."'");
                }else{
                    // Insert member data in the database
                    $db->query("INSERT INTO student_registrations(matric_number,study_center,study_center_code,course,exam_day,exam_session,exa_date) VALUES ('".$matric."','".$studycentre."','".$centrecode."','".$course."','".$examday."','".$examsession."','".$examdate."')");
                }
            }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: reg-data.php".$qstring);