<?php
/**
 * Export Registration Data to Excel
 * 
 * @package QSEND
 * @version 2.0
 */

include 'includes/session.php';

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="registration_data_' . date('Y-m-d') . '.xls"');

// Create Excel content
echo "<table border='1'>";
echo "<tr>";
echo "<th>Matric Number</th>";
echo "<th>Study Center</th>";
echo "<th>Center Code</th>";
echo "<th>Course</th>";
echo "<th>Exam Day</th>";
echo "<th>Exam Session</th>";
echo "<th>Exam Date</th>";
echo "</tr>";

$sql = "SELECT * FROM student_registrations ORDER BY id DESC";
$query = $conn->query($sql);

while($fetch = $query->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($fetch['matric_number']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['study_center']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['study_center_code']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['course']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['exam_day']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['exam_session']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['exa_date']) . "</td>";
    echo "</tr>";
}

echo "</table>";
?>

