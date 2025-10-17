<?php
/**
 * Export Sent Items to Excel
 * 
 * @package QSEND
 * @version 2.0
 */

include 'includes/session.php';

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="sent_items_' . date('Y-m-d') . '.xls"');

// Create Excel content
echo "<table border='1'>";
echo "<tr>";
echo "<th>Center Code</th>";
echo "<th>Center Name</th>";
echo "<th>Exam Session</th>";
echo "<th>Exam Day</th>";
echo "<th>Password</th>";
echo "<th>Date Sent</th>";
echo "<th>Sent By</th>";
echo "<th>IP Address</th>";
echo "</tr>";

$sql = "SELECT * FROM files WHERE sem=251 ORDER BY created_at DESC";
$query = $conn->query($sql);

while($fetch = $query->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($fetch['student_center_name']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['study_center']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['exam_session']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['exam_day']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['password']) . "</td>";
    echo "<td>" . date('Y-m-d H:i:s', strtotime($fetch['created_at'])) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['sentby']) . "</td>";
    echo "<td>" . htmlspecialchars($fetch['ip_address']) . "</td>";
    echo "</tr>";
}

echo "</table>";
?>

