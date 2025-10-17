<?php
// Include your database connection file
include 'connection.php';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query to retrieve all records
$sql = "SELECT *, files.id AS id FROM files where sem=231";
$query = $conn->query($sql);
$output = '';

if ($query->num_rows > 0) {
    while ($fetch = $query->fetch_assoc()) {
        $output .= '<tr>';
        $output .= '<td>' . $fetch['student_center_name'] . '</td>';
        $output .= '<td>' . $fetch['study_center'] . '</td>';
        $output .= '<td>' . $fetch['exam_session'] . '</td>';
        $output .= '<td>' . $fetch['exam_day'] . '</td>';
        $output .= '<td>' . $fetch['password'] . '</td>';
        $output .= '<td>' . date('dS F Y h:s a', strtotime($fetch['created_at'])) . '</td>';
        $output .= '<td> <a href="' . $fetch['file_name'] . '">Download</a> </td>';
        $output .= '</tr>';
    }
} else {
    $output .= '<tr><td colspan="7">No data found</td></tr>';
}

echo $output;
?>
