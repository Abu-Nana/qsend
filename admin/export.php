<?php
// Define the filename
$filename = "exam_export_" . $_GET['exam_day'] . "_" . $_GET['section'] . ".csv";

// Set appropriate headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Write the column headers for the CSV file
fputcsv($output, array('ID', 'Study Center', 'Student Center Name', 'Exam Session', 'Exam Day', 'Password', 'File Name', 'SEM', 'Created At'));

// Secure Database Connection
$connection = require 'connection.php';

// Fetch data from the database
$query = "SELECT * FROM files WHERE exam_day = '" . $_GET['exam_day'] . "' AND study_center = '" . $_GET['section'] . "'";
$result = $connection->query($query);

// Loop through the data and write to the CSV file
while ($row = $result->fetch_assoc()) {
    fputcsv($output, array(
        $row['id'],
        $row['study_center'],
        $row['student_center_name'],
        $row['exam_session'],
        $row['exam_day'],
        $row['password'],
        $row['file_name'],
        $row['sem'],
        $row['created_at']
    ));
}

// Close the database connection
$connection->close();

// Close the file pointer
fclose($output);
?>
